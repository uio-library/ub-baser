<?php

namespace App\Services;

use App\Base;
use App\Http\Requests\SearchRequest;
use App\Schema\Operators;
use App\Schema\Schema;
use App\Schema\SearchOptions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class QueryBuilder
{
    /**
     * @var SearchRequest
     */
    protected $request;

    /**
     * @var Base
     */
    protected $base;

    /**
     * @var Schema
     */
    protected $schema;

    /**
     * @var Builder
     */
    protected $query;

    /**
     * @var array
     */
    protected $fields;

    /**
     * Construct an Eloquent query builder from a search request.
     *
     * @param SearchRequest $request
     * @return Builder
     */
    public static function fromRequest(SearchRequest $request)
    {
        $instance = new static($request);
        return $instance->process();
    }

    public function __construct(SearchRequest $request, Base $base = null)
    {
        $this->request = $request;
        $this->base = $base ?: app(Base::class);
        $this->schema = $this->base->getSchema();
        $this->fields = $this->schema->keyed();
    }

    public function process()
    {
        $this->query = $this->base->getClass('RecordView')::query();

        foreach ($this->request->getQueryParts() as $queryPart) {
            $field = $this->fields[$queryPart['field']];
            $operator = $queryPart['operator'] ?: $field->getDefaultSearchOperator();
            $value = $queryPart['value'];

            if ($operator === Operators::IS_NULL) {
                if ($field->search->type === 'array') {
                    $this->query->whereRaw($field->getColumn() . " = '[]'::jsonb");
                } else {
                    $this->query->whereNull($field->getColumn());
                }
                continue;
            } elseif ($operator === Operators::NOT_NULL) {
                if ($field->search->type === 'array') {
                    $this->query->whereRaw($field->getColumn() . " != '[]'::jsonb");
                } else {
                    $this->query->whereNotNull($field->getColumn());
                }
                continue;
            }

            $searchType = $field->search->type;
            if (in_array($operator, [Operators::BEGINS_WITH, Operators::ENDS_WITH])) {
                $searchType = 'simple';
            }

            switch ($searchType) {
                case 'ts':
                    $this->addTextSearchTerm($field->search, $operator, $value);
                    break;
                case 'range':
                    $this->addRangeSearchTerm($field->search, $operator, $value);
                    break;
                case 'array':
                    $this->addArraySearchTerm($field->search, $operator, $value);
                    break;
                default:
                    $this->addSimpleTerm($field->search, $operator, $value);
            }
        }

        return $this->query;
    }

    protected function addTextSearchTerm(SearchOptions $searchConfig, string $operator, ?string $value): void
    {
        if (Str::startsWith($value, '"') && Str::endsWith($value, '"')) {
            // Phrase
            $query = "phraseto_tsquery('simple', ?)";
        } elseif (Str::endsWith($value, '*')) {
            // Prefix / ending wildcard
            $query = "(phraseto_tsquery('simple', ?)::text || ':*')::tsquery";
        } else {
            // Keyword
            $query = "plainto_tsquery('simple', ?)";
        }

        switch ($operator) {
            case Operators::CONTAINS:
                $this->query->whereRaw($searchConfig->ts_index . ' @@ ' . $query, [$value]);
                break;
            case Operators::NOT_CONTAINS:
                $this->query->whereRaw('NOT ' . $searchConfig->ts_index . ' @@ ' . $query, [$value]);
                break;
            default:
                throw new \RuntimeException('Unsupported search operator');
        }
    }

    protected function addSimpleTerm(SearchOptions $searchConfig, string $operator, ?string $value): void
    {
        switch ($operator) {
            case Operators::CONTAINS:
            case Operators::NOT_CONTAINS:
                if (Str::startsWith($value, '"') && Str::endsWith($value, '"')) {
                    // Phrase
                    $value = Str::substr($value, 1, Str::length($value) - 1);
                    $operator = ($operator === Operators::CONTAINS) ? Operators::EQUALS : Operators::NOT_CONTAINS;
                } elseif (Str::startsWith($value, '*') && Str::endsWith($value, '*')) {
                    $value = '%' . trim($value, '*') . '%';
                } elseif (Str::startsWith($value, '*')) {
                    // Suffix / starting wildcard
                    $value = '%' . ltrim($value, '*');
                } elseif (Str::endsWith($value, '*')) {
                    // Prefix / ending wildcard
                    $value = rtrim($value, '*') . '%';
                } else {
                    // left and right-truncate by default
                    $value = '%' . $value . '%';
                }
                break;

            case Operators::ENDS_WITH:
                // Suffix / starting wildcard
                $value = '%' . $value;
                break;

            case Operators::BEGINS_WITH:
                // Prefix / ending wildcard
                $value = $value . '%';
                break;

            case Operators::EQUALS:
            case Operators::NOT_EQUALS:
                if (Str::endsWith($value, '*')) {
                    // Prefix / ending wildcard
                    $value = rtrim($value, '*') . '%';
                    $operator = Operators::LIKE;
                }
        }

        if ($searchConfig->case === Schema::UPPER_CASE) {
            $value = mb_strtoupper($value);
        } elseif ($searchConfig->case === Schema::LOWER_CASE) {
            $value = mb_strtolower($value);
        }

        $operatorMap = [
            Operators::EQUALS => '=',
            Operators::NOT_EQUALS => '<>',
            Operators::CONTAINS => 'ILIKE',
            Operators::NOT_CONTAINS => 'NOT ILIKE',
            Operators::BEGINS_WITH => 'ILIKE',
            Operators::ENDS_WITH => 'ILIKE',
        ];

        if (!isset($operatorMap[$operator])) {
            throw new \Error('Unsupported search operator "' . $operator . '"');
        }

        $sqlOperator = $operatorMap[$operator];
        $this->query->whereRaw("{$searchConfig->index} {$sqlOperator} ?", [$value]);
    }

    protected function addRangeSearchTerm(SearchOptions $searchConfig, string $operator, ?string $value): void
    {
        $value = explode('-', $value);
        if (count($value) == 2) {
            switch ($operator) {
                case Operators::IN_RANGE:
                    $this->query->where($searchConfig->index, '>=', intval($value[0]))
                        ->where($searchConfig->index, '<=', intval($value[1]));
                    return;
                case Operators::OUTSIDE_RANGE:
                    $this->query->where($searchConfig->index, '<', intval($value[0]))
                        ->orWhere($searchConfig->index, '>', intval($value[1]));
                    return;
            }
        }
        throw new \Error('Unsupported search operator');
    }

    protected function addArraySearchTerm(SearchOptions $searchConfig, string $operator, ?string $value): void
    {
        switch ($operator) {
            case Operators::IS:
                // Note: The ~@ operator is defined in <2015_12_13_120034_add_extra_operators.php>
                $this->query->whereRaw($searchConfig->index . ' ~@ ?', [$value]);
                break;
            case Operators::NOT:
                // Note: The ~@ operator is defined in <2015_12_13_120034_add_extra_operators.php>
                $this->query->whereRaw('NOT ' . $searchConfig->index . ' ~@ ?', [$value]);
                break;
            default:
                throw new \Error('Unsupported search operator');
        }
        // TODO: Support wildcards in some way.
        // "Contains" could be done easily as column::text like '%value%'
        // Starts with is a bit worse :/
    }
}
