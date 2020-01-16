<?php

namespace App\Bases\Opes;

use App\Schema\Schema as BaseSchema;

class Schema extends BaseSchema
{
    public $prefix = 'opes';

    protected $schema = [
        'fields' => [

            // ID
            [
                'key' => 'id',
                'type' => 'incrementing',
                'displayable' => true,
            ],

            // Inventary number
            [
                'key' => 'inv_no',
                'type' => 'simple',
            ],

            // Opprettet
            [
                'key' => 'created_at',
                'type' => 'simple',
                'editable' => false,
                'searchable' => 'disabled',

                'columnClassName' => 'dt-body-nowrap',
            ],

            // Sist endret
            [
                'key' => 'updated_at',
                'type' => 'simple',
                'editable' => false,
                'searchable' => 'disabled',

                'columnClassName' => 'dt-body-nowrap',
            ],

            // Title or type
            [
                'key' => 'title_or_type',
                'type' => 'simple',
            ],
        ],

        'groups' => [

            [
                'label' => 'Background and Physical Properties',
                'fields' => [

                    // Material
                    [
                        'key' => 'material',
                        'type' => 'autocomplete',
                    ],

                    // Material
                    [
                        'key' => 'connections',
                        'type' => 'simple',
                    ],

                    // Size
                    [
                        'key' => 'size',
                        'type' => 'simple',
                    ],

                    // ...osv.

                ],
            ],

            [
                'label' => 'Contents',
                'fields' => [

                    // Date
                    [
                        'key' => 'date',
                        'type' => 'simple',
                    ],

                    // Origin
                    [
                        'key' => 'origin',
                        'type' => 'simple',
                    ],

                    // ...osv.

                ],
            ],

            // WAIT with this:
            // [
            //     'label' => 'Publications',
            //     'fields' => [
            //     ],
            // ],

        ],
    ];
}
