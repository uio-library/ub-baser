<?php

namespace App\Bases\Litteraturkritikk;

class AggregateLists
{
    protected $lists = [
        [
            'id' => 1,
            'label' => 'Liste over emneord',
            'type' => 'array',
            'field' => 'tags',
        ],
        [
            'id' => 2,
            'label' => 'Liste over kritikktyper',
            'type' => 'array',
            'field' => 'kritikktype',
        ],
        [
            'id' => 3,
            'label' => 'Liste over språk',
            'type' => 'simple',
            'field' => 'spraak',
        ],
        [
            'id' => 4,
            'label' => 'Liste over publikasjoner',
            'type' => 'simple',
            'field' => 'publikasjon',
        ],
        [
            'id' => 5,
            'label' => 'Omtalt verk: Liste over språk',
            'type' => 'simple',
            'field' => 'verk_spraak',
        ],
        [
            'id' => 6,
            'label' => 'Omtalt verk: Liste over originalspråk',
            'type' => 'simple',
            'field' => 'verk_originalspraak',
        ],
        [
            'id' => 7,
            'label' => 'Omtalt verk: Liste over sjangre',
            'type' => 'simple',
            'field' => 'verk_sjanger',
        ],
    ];

    public function __construct()
    {
        $this->lists = collect($this->lists);
    }

    public function all(): array
    {
        $out = [];
        foreach ($this->lists as $list) {
            $out[] = new AggregateList($list);
        }
        return $out;
    }

    public function get(int $id): AggregateList
    {
        $list = $this->lists->firstWhere('id', '=', $id);
        if (is_null($list)) {
            return null;
        }

        return new AggregateList($list);
    }
}
