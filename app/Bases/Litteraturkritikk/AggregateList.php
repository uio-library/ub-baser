<?php

namespace App\Bases\Litteraturkritikk;

use Illuminate\Support\Arr;

class AggregateList
{
    public $id;
    public $type;
    public $field;
    public $label;

    public function __construct(array $data)
    {
        $this->id = Arr::get($data, 'id');
        $this->type = Arr::get($data, 'type');
        $this->field = Arr::get($data, 'field');
        $this->label = Arr::get($data, 'label');
    }

    protected function sanitize(string $value)
    {
        return preg_replace('/[^a-z0-9_]/', '', $value);
    }

    public function getResults(string $sort = null): array
    {
        $orderBy = ($sort == 'value') ? 'value asc' : 'record_count desc, value asc';

        if ($this->type === 'array') {
            return \DB::select(
                'SELECT
                value, count(t.*) as record_count
                from litteraturkritikk_records_search t
                   , jsonb_array_elements_text(t.' . $this->sanitize($this->field) . ') value
                group by value
                order by ' . $orderBy
            );
        }

        return \DB::select(
            'SELECT
            t.' . $this->sanitize($this->field) . ' as value, count(t.*) as record_count
            from litteraturkritikk_records_search t
            group by value
            order by ' . $orderBy
        );
    }
}
