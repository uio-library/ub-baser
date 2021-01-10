<?php

namespace App\Bases\Lover;

use App\Schema\SchemaField;
use Illuminate\Support\Facades\DB;

class AutocompleteService extends \App\Bases\AutocompleteService
{
    /**
     * The completer method to use if no field-specific completer was found.
     * If set to null, an error will be thrown if no completer was found.
     *
     * @var string
     */
    // protected $defaultCompleter = null;

    /**
     * The lister method to use if no field-specific completer was found.
     * If set to null, an error will be thrown if no lister was found.
     *
     * @var string
     */
    // protected $defaultLister = null;

    /**
     * Lister methods to use with each field.
     *
     * @var array
     */
    protected $listers = [
        'oversettelse_sprak' => 'sprakLister',
    ];

    /**
     * Lister for the "oversettelse_sprak" field.
     *
     * @param SchemaField $field
     * @return array
     */
    protected function sprakLister(SchemaField $field): array
    {
        $query = Oversettelse::select(DB::raw('count(*) as poster, sprak'))
            ->groupBy(['sprak']);

        $out = [];
        foreach ($query->get() as $row) {
            $out[] = [
                'prefLabel' => trans('oversatte_lover.sprak.' . $row->sprak),
                'value' => $row->sprak,
                'count' => $row->poster,
            ];
        }
        return $out;
    }
}
