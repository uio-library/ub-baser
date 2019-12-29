<?php

namespace App\Http\Requests;

use App\Base;
use App\Http\Request;
use App\Services\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class SearchRequest extends Request
{
    public function getFields(): array
    {
        return app(Base::class)->getSchema()->keyed();
    }

    public function makeQueryBuilder(): Builder
    {
        return QueryBuilder::fromRequest($this);
    }

    /**
     * Turns ['input1field' => 'A', 'input1value' => 'B', 'input2field' => 'C', 'input2value' => 'D', ...]
     * into ['A' => 'B', 'C' => 'D', ...] and [['A', 'B'], ['C', 'D']].
     *
     * @return array
     */
    public function getQueryParts(): array
    {
        $fields = $this->getFields();
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
            if (!isset($fields[$fieldName])) {
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
}
