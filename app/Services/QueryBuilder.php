<?php

namespace App\Services;

use App\Base;
use App\Http\Requests\SearchRequest;
use App\Schema\Operators;
use App\Schema\Schema;
use App\Schema\SearchOptions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
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
    public static function fromDataTableRequest(SearchRequest $request): EloquentBuilder
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

    public function process(): EloquentBuilder
    {
        $this->query = $this->base->getClass('RecordView')::query();

        $boolean = 'and';
        foreach ($this->request->parseQuery() as $queryPart) {
            $this->query->where(function ($query) use ($queryPart) {
                $this->addQueryPart($query, $queryPart);
            }, null, null, $boolean);
            $boolean = $queryPart['boolean'];
        }
        return $this->query;
    }

    public function addQueryPart(EloquentBuilder $query, array $input): void
    {
        $field = $this->fields[$input['field']];
        $operator = $input['operator'] ?: $field->getDefaultSearchOperator();
        $value = $input['value'];

        if ($operator === Operators::IS_NULL) {
            if ($field->search->type === 'array') {
                $query->whereRaw($field->getColumn() . " = '[]'::jsonb");
            } else {
                $query->whereNull($field->getColumn());
            }
            return;
        } elseif ($operator === Operators::NOT_NULL) {
            if ($field->search->type === 'array') {
                $query->whereRaw($field->getColumn() . " != '[]'::jsonb");
            } else {
                $query->whereNotNull($field->getColumn());
            }
            return;
        }

        $searchType = $field->search->type;
        if (in_array($operator, [Operators::BEGINS_WITH, Operators::ENDS_WITH])) {
            $searchType = 'simple';
        }

        switch ($searchType) {
            case 'ts':
                $this->addTextSearchTerm($query, $field->search, $operator, $value);
                return;
            case 'range':
                $this->addRangeSearchTerm($query, $field->datatype, $field->search, $operator, $value);
                return;
            case 'array':
                $this->addArraySearchTerm($query, $field->search, $operator, $value);
                return;
            default:
                $this->addSimpleTerm($query, $field->search, $operator, $value);
                return;
        }
    }

    protected function addTextSearchTerm(EloquentBuilder $query, SearchOptions $searchConfig, string $operator, ?string $value): void
    {
        if (Str::startsWith($value, '"') && Str::endsWith($value, '"')) {
            // Phrase
            $queryTerm = "phraseto_tsquery('simple', ?)";
        } elseif (Str::endsWith($value, '*')) {
            // Prefix / ending wildcard
            $queryTerm = "(phraseto_tsquery('simple', ?)::text || ':*')::tsquery";
        } else {
            // Keyword
            $queryTerm = "plainto_tsquery('simple', ?)";
        }

        switch ($operator) {
            case Operators::CONTAINS:
                $query->whereRaw($searchConfig->ts_index . ' @@ ' . $queryTerm, [$value]);
                break;
            case Operators::NOT_CONTAINS:
                $query->whereRaw('NOT ' . $searchConfig->ts_index . ' @@ ' . $queryTerm, [$value]);
                break;
            default:
                throw new \RuntimeException('Unsupported search operator');
        }
    }

    protected function addSimpleTerm(EloquentBuilder $query, SearchOptions $searchConfig, string $operator, ?string $value): void
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
                if (Str::endsWith($value, '*')) {
                    // Prefix / ending wildcard
                    $value = rtrim($value, '*') . '%';
                    $operator = Operators::BEGINS_WITH;
                }
                break;

            case Operators::NOT_EQUALS:
                // @TODO
                // if (Str::endsWith($value, '*')) {
                //     // Prefix / ending wildcard
                //     $value = rtrim($value, '*') . '%';
                //     $operator = Operators::NOT_BEGINS_WITH;
                // }
                break;
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
        $query->whereRaw("{$searchConfig->index} {$sqlOperator} ?", [$value]);
    }

    protected function addRangeSearchTerm(
        EloquentBuilder $query,
        string $datatype,
        SearchOptions $searchConfig,
        string $operator,
        ?string $value
    ): void
    {
        $value = explode('-', $value); // TODO: Endre til '/' for å kunne støtte datoer med MM, DD også?
        if (count($value) == 2) {
            if ($datatype === Schema::DATATYPE_DATE) {
                $range = [$this->toDate($value[0], false), $this->toDate($value[1], true)];
            } else {
                $range = [intval($value[0]),  intval($value[1])];
            }
            if ($operator === Operators::IN_RANGE) {
                $query->whereBetween($searchConfig->index, $range);
                return;
            } elseif ($operator === Operators::OUTSIDE_RANGE) {
                $query->whereNotBetween($searchConfig->index, $range);
                return;
            }
        }

        throw new \Error('Unsupported search operator');
    }

    protected function addArraySearchTerm(EloquentBuilder $query, SearchOptions $searchConfig, string $operator, ?string $value): void
    {
        switch ($operator) {
            case Operators::EQUALS:
                // Note: The ~@ operator is defined in <2015_12_13_120034_add_extra_operators.php>
                $query->whereRaw($searchConfig->index . '::jsonb ~@ ?', [$value]);
                break;
            case Operators::NOT_EQUALS:
                // Note: The ~@ operator is defined in <2015_12_13_120034_add_extra_operators.php>
                $query->whereRaw('NOT ' . $searchConfig->index . '::jsonb ~@ ?', [$value]);
                break;
            default:
                throw new \Error('Unsupported search operator');
        }
        // TODO: Support wildcards in some way.
        // "Contains" could be done easily as column::text like '%value%'
        // Starts with is a bit worse :/
    }

    /**
     * Converts input to YYYY-MM-DD
     *
     * @param string $value
     * @param bool $inclusive
     * @return string
     */
    protected function toDate(string $value, bool $inclusive = false): string
    {
        if (mb_strlen($value) === 4) {
            if ($inclusive) {
                return $value . '-12-31';
            }
            return $value . '-01-01';
        }
        throw new \RuntimeException('Unknown date format');
    }
}
