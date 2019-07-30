<?php

namespace App\Http\Requests;

use App\BaseSchema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class SearchRequest extends FormRequest
{
    protected $fields;
    public $queryParts;
    public $queryBuilder;
    protected $defaultOperator = 'eq';

    abstract protected function getSchema(): BaseSchema;

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
        $fields = [];
        foreach ($this->all() as $key => $fieldName) {
            if (preg_match('/^f([0-9]+)$/', $key, $matches)) {
                $idx = $matches[1];
                $value = Arr::get($this, "v$idx");
                $operator = Arr::get($this, "o$idx", $this->defaultOperator);

                if (!is_null($value) || in_array($operator, ['isnull', 'notnull'])) {
                    $fields[] = [
                        'field' => $fieldName,
                        'operator' => $operator,
                        'value' => $value,
                    ];
                }
            }
        }

        $input = [];
        foreach ($fields as $field) {
            if (isset($this->fields[$field['field']])) {
                $input[] = $field;
            }
        }

        return $input;
    }

    protected function main(): void
    {
        $this->init();

        // Create an instances of \Illuminate\Database\Eloquent\Builder
        $this->queryBuilder = $this->makeQueryBuilder();

        $this->queryParts = $this->getInput();

        foreach ($this->queryParts as $queryPart) {
            $field = $this->fields[$queryPart['field']];

            $indexType = Arr::get($field, 'search.index.type');
            $index = Arr::get($field, 'search.index');

            $operator = $queryPart['operator'];
            $value = $queryPart['value'];

            if (is_null($index)) {
                $index = [
                    'column' => $field['key'],
                ];
            }

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
        if (Str::startsWith($value, '"') && Str::endsWith($value, '"')) {
            // Phrase
            $value = Str::substr($value, 1, Str::length($value) - 1);
        } elseif (Str::endsWith($value, '*')) {
            // Prefix / ending wildcard
            $value = $value . '%';
        } else {
            // Keyword
            $value = '%' . $value . '%';
        }

        switch ($operator) {
            case 'eq':
                $this->queryBuilder->where($index['column'], 'ilike', $value);
                break;
            case 'neq':
                $this->queryBuilder->where($index['column'], 'not ilike', $value);
                break;
            default:
                $this->checkNull($index['column'], $operator);
        }
    }

    protected function addRangeSearchTerm(array $index, string $operator, ?string $value): void
    {
        $value = explode('-', $value);

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
