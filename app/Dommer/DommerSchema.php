<?php

namespace App\Dommer;

use App\BaseSchema;

class DommerSchema extends BaseSchema
{
    public $prefix = 'dommer';

    protected $schema = [
        'fields' => [

            // ID
            [
                'key' => 'id',
                'type' => 'incrementing',
                'display' => false,
                'edit' => false,
                'search' => false,
            ],

            [
                'key' => 'navn',
                'type' => 'simple',
            ],
            [
                'key' => 'kilde',
                'type' => 'select',
                'model_attribute' => 'kilde',
                'display' => [
                    'column' => 'kilde_navn',
                ],
                'edit' => [
                    'column' => 'kilde_id',
                ],
                'search' => [
                    'index' => [
                        'type'=> 'simple',
                        'column' => 'kilde_navn',
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
        $this->init([
            'autocompleTarget' => action('DommerController@autocomplete'),
        ]);
    }
}