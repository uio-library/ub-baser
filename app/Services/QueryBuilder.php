<?php

namespace App\Services;

use App\Base;
use App\Http\Requests\SearchRequest;
use App\Schema\Schema;
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
            $operator = $queryPart['operator'];
            if (!$operator) {
                $operator = $field->getDefaultSearchOperator();
            }
            $value = $queryPart['value'];

            $index = $field->get('searchOptions.index', [
                'column' => $field->key,
            ]);

            $indexType = Arr::get($index, 'type');

            if ($indexType == 'ts') {
                $this->addTextSearchTerm($index, $operator, $value);
            } elseif ($indexType == 'range') {
                $this->addRangeSearchTerm($index, $operator, $value);
            } elseif ($indexType == 'array') {
                $this->addArraySearchTerm($index, $operator, $value);
            } else {
                $this->addSimpleTerm($index, $operator, $value);
            }
        }

        return $this->query;
    }

    protected function checkNull(string $column, string $operator): void
    {
        switch ($operator) {
            case 'isnull':
                $this->query->whereNull($column);
                break;
            case 'notnull':
                $this->query->whereNotNull($column);
                break;
            default:
                throw new \RuntimeException('Unsupported search operator');
        }
    }

    protected function addTextSearchTerm(array $index, string $operator, ?string $value): void
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
            case 'eq':
                $this->query->whereRaw($index['ts_column'] . ' @@ ' . $query, [$value]);
                break;
            case 'neq':
                $this->query->whereRaw('NOT ' . $index['ts_column'] . ' @@ ' . $query, [$value]);
                break;
            default:
                $this->checkNull($index['column'], $operator);
        }
    }

    protected function addSimpleTerm(array $index, string $operator, ?string $value): void
    {
        if ($operator == 'eq' || $operator == 'like') {
            if (Str::startsWith($value, '"') && Str::endsWith($value, '"')) {
                // Phrase
                $value = Str::substr($value, 1, Str::length($value) - 1);
                $operator = 'ex';
            } elseif (Str::startsWith($value, '*')) {
                // Prefix / ending wildcard
                $value = '%' . trim($value, '*') . '%';
            } elseif (Str::endsWith($value, '*')) {
                // Prefix / ending wildcard
                $value = rtrim($value, '*') . '%';
            } else {
                // right-truncate by default
                $value = $value . '%';
            }
        } elseif ($operator == 'ex') {
            if (Str::endsWith($value, '*')) {
                // Prefix / ending wildcard
                $value = rtrim($value, '*') . '%';
                $operator = 'like';
            }
        }

        if (isset($index['case'])) {
            if ($index['case'] == Schema::UPPER_CASE) {
                $value = mb_strtoupper($value);
            }
            if ($index['case'] == Schema::LOWER_CASE) {
                $value = mb_strtolower($value);
            }
        }

        switch ($operator) {

            // Exact match operator, we use raw to support complex column arguments like e.g. "lower(name)"
            case 'ex':
                $this->query->whereRaw($index['column'] . ' = ?', [$value]);
                break;

            // Like operator
            case 'like':
                $this->query->whereRaw($index['column'] . ' like ?', [$value]);
                break;

            // Standard ilike operator
            case 'eq':
                $this->query->whereRaw($index['column'] . ' ilike ?', [$value]);
                break;

            // Negated standard ilike operator
            case 'neq':
                $this->query->whereRaw($index['column'] . ' not ilike ?', [$value]);
                break;

            default:
                $this->checkNull($index['column'], $operator);
        }
    }

    protected function addRangeSearchTerm(array $index, string $operator, ?string $value): void
    {
        $value = explode('-', $value);
        if (count($value) != 2) {
            return;
        }

        switch ($operator) {
            case 'eq':
                $this->query->where($index['column'], '>=', intval($value[0]))
                    ->where($index['column'], '<=', intval($value[1]));
                break;
            case 'neq':
                $this->query->where($index['column'], '<', intval($value[0]))
                    ->orWhere($index['column'], '>', intval($value[1]));
                break;
            default:
                $this->checkNull($index['column'], $operator);
        }
    }

    protected function addArraySearchTerm(array $index, string $operator, ?string $value): void
    {
        switch ($operator) {
            case 'eq':
                // Note: The ~@ operator is defined in <2015_12_13_120034_add_extra_operators.php>
                $this->query->whereRaw($index['column'] . ' ~@ ?', [$value]);
                break;
            case 'neq':
                // Note: The ~@ operator is defined in <2015_12_13_120034_add_extra_operators.php>
                $this->query->whereRaw('NOT ' . $index['column'] . ' ~@ ?', [$value]);
                break;
            default:
                $this->checkNull($index['column'], $operator);
        }
    }
}
