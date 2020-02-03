<?php

namespace App\Bases\Bibsys;

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
     * Completer methods to use with each field.
     *
     * @var array
     */
    protected $completers = [
    ];
    /**
     * Lister methods to use with each field.
     *
     * @var array
     */
    protected $listers = [
    ];

    /**
     * @param SchemaField $field
     * @param string $term
     * @return array
     */
    protected function bibsysCompleter(SchemaField $field, string $term): array
    {
        /*
        $index = $field->get('search.index', [
            'column' => $field->key,
        ]);
        $searchColumn = $index['column'];
        $viewColumn = $field->getColumn();

        $data = [];

        $query = $this->newQuery()
            ->select("{$viewColumn} as value")
            ->whereRaw($searchColumn . ' like ?', [$term . '%']);

        return $this->getResultsOrderedByPopularity($query);
        */
    }
}
