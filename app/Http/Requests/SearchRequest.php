<?php

namespace App\Http\Requests;

use App\Schema\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class SearchRequest extends FormRequest
{
    protected $fields;
    public $queryParts;

    /** @var \Illuminate\Database\Eloquent\Builder */
    public $queryBuilder;

    abstract protected function getSchema(): Schema;

    abstract protected function makeQueryBuilder(): Builder;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $this->main();

        return [];
    }

    protected function init(): void
    {
        $this->fields = $this->getSchema()->keyed();
    }

    /**
     * Turns ['input1field' => 'A', 'input1value' => 'B', 'input2field' => 'C', 'input2value' => 'D', ...]
     * into ['A' => 'B', 'C' => 'D', ...] and [['A', 'B'], ['C', 'D']].
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function getInput(): array
    {
        $inputs = [];
        foreach ($this->all() as $key => $fieldName) {
            if (!preg_match('/^f([0-9]+)$/', $key, $matches)) {
                continue;
            }
            $idx = $matches[1];
            $value = Arr::get($this, "v$idx");
            $operator = Arr::get($this, "o$idx");

            if ($value === null && !in_array($operator, ['isnull', 'notnull'])) {
                continue;
            }
            if (!isset($this->fields[$fieldName])) {
                continue;
            }
            $inputs[] = [
                'field' => $fieldName,
                'operator' => $operator,
                'value' => $value,
            ];
        }

        return $inputs;
    }

    protected function main(): void
    {
        $this->init();

        // Create an instances of \Illuminate\Database\Eloquent\Builder
        $this->queryBuilder = $this->makeQueryBuilder();

        $this->queryParts = $this->getInput();

        foreach ($this->queryParts as $queryPart) {
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
    }

    protected function checkNull(string $column, string $operator): void
    {
        switch ($operator) {
            case 'isnull':
                $this->queryBuilder->whereNull($column);
                break;
            case 'notnull':
                $this->queryBuilder->whereNotNull($column);
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
                $this->queryBuilder->whereRaw($index['ts_column'] . ' @@ ' . $query, [$value]);
                break;
            case 'neq':
                $this->queryBuilder->whereRaw('NOT ' . $index['ts_column'] . ' @@ ' . $query, [$value]);
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
                $this->queryBuilder->whereRaw($index['column'] . ' = ?', [$value]);
                break;

            // Like operator
            case 'like':
                $this->queryBuilder->whereRaw($index['column'] . ' like ?', [$value]);
                break;

            // Standard ilike operator
            case 'eq':
                $this->queryBuilder->whereRaw($index['column'] . ' ilike ?', [$value]);
                break;

            // Negated standard ilike operator
            case 'neq':
                $this->queryBuilder->whereRaw($index['column'] . ' not ilike ?', [$value]);
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
                $this->queryBuilder->where($index['column'], '>=', intval($value[0]))
                    ->where($index['column'], '<=', intval($value[1]));
                break;
            case 'neq':
                $this->queryBuilder->where($index['column'], '<', intval($value[0]))
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
                $this->queryBuilder->whereRaw($index['column'] . ' ~@ ?', [$value]);
                break;
            case 'neq':
                // Note: The ~@ operator is defined in <2015_12_13_120034_add_extra_operators.php>
                $this->queryBuilder->whereRaw('NOT ' . $index['column'] . ' ~@ ?', [$value]);
                break;
            default:
                $this->checkNull($index['column'], $operator);
        }
    }
}
