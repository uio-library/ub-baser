<?php

namespace App\Bases\Dommer;

use App\Schema\SchemaField;

class AutocompleteService extends \App\Services\AutocompleteService
{
    /**
     * The completer method to use if no field-specific completer was found.
     * If set to null, an error will be thrown if no completer was found.
     *
     * @var string
     */
    protected $defaultCompleter = null;

    /**
     * The lister method to use if no field-specific completer was found.
     * If set to null, an error will be thrown if no lister was found.
     *
     * @var string
     */
    protected $defaultLister = null;

    /**
     * Lister methods to use with each field.
     *
     * @var array
     */
    protected $listers = [
        'kilde' => 'kildeLister',
    ];

    /**
     * Lister for the "kilde" field.
     *
     * @param SchemaField $field
     * @return array
     */
    protected function kildeLister(SchemaField $field): array
    {
        $query = \DB::table('dommer_kilder')
            ->select(['id', 'navn as value']);

        $out = [];
        foreach ($query->get() as $row) {
            $out[] = [
                'prefLabel' => $row->id,
                'label' => $row->value,
            ];
        }
        return $out;
    }
}
