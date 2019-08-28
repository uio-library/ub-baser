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

        parent::__construct();
    }
}
