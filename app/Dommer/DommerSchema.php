<?php

namespace App\Dommer;

use App\Schema\Schema;

class DommerSchema extends Schema
{
    public $prefix = 'dommer';

    protected $schema = [
        'fields' => [

            // ID
            [
                'key' => 'id',
                'type' => 'incrementing',
            ],

            [
                'key' => 'navn',
                'type' => 'simple',
            ],
            [
                'key' => 'kilde',
                'type' => 'select',

                'column' => 'kilde_id',
                'viewColumn' => 'kilde_navn',

                'searchOptions' => [
                    'index' => [
                        'type'=> 'simple',
                        'column' => 'kilde_id',
                    ],
                ],
            ],
            [
                'key' => 'aar',
                'type' => 'simple',

                'columnClassName' => 'dt-body-nowrap',

                'searchOptions' => [
                    'type' => 'rangeslider',
                    'index' => [
                        'type' => 'range',
                        'column' => 'aar',
                    ],
                ],
            ],
            [
                'key' => 'side',
                'type' => 'simple',
            ],
        ],

        'groups' => [],
    ];

    public function __construct()
    {
        $this->schemaOptions['autocompleteUrl'] = action('DommerController@autocomplete');
        $this->schemaOptions['minYear'] = 1848;
        $this->schemaOptions['maxYear'] = 2012;

        parent::__construct();
    }
}
