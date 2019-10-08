<?php

namespace App\Bibsys;

use App\Schema\Schema;

class BibsysSchema extends Schema
{
    public $prefix = 'bibsys';
    public $primaryId = 'dokid';

    protected $schema = [
        'fields' => [
            // Søk i alle felt
            [
                'key' => 'any_field_ts',
                'type' => 'simple',

                'displayable' => false,
                'editable' => false,
                'searchable' => 'simple',

                'searchOptions' => [
                    'placeholder' => 'Du kan søke etter objektid, dokid, knyttid, avdeling, samling, tekst i MARC-posten, osv.',
                    'index' => ['type' => 'ts', 'ts_column' => 'any_field_ts'],
                    'operators' => ['eq', 'neq']
                ],
            ],
        ],

        'groups' => [
            [
                'label' => 'Objektpost',
                'fields' => [

                    [
                        'key' => 'objektid',
                        'type' => 'simple',

                        'searchOptions' => [
                            'operators' => ['ex'],
                        ],
                        'orderable' => false,
                    ],

                    [
                        'key' => 'title_statement',
                        'type' => 'simple',
                        'orderable' => false,
                        'searchable' => 'advanced',
                    ],

                    [
                        'key' => 'pub_date',
                        'type' => 'simple',
                        'orderable' => false,
                        'searchable' => 'advanced',
                    ],

                    [
                        'key' => 'marc_record',
                        'type' => 'simple',
                        'orderable' => false,
                        'searchable' => 'advanced',
                    ],
                ]
            ],
            [
                'label' => 'Dokumentpost',
                'fields' => [

                    // Dokid
                    [
                        'key' => 'dokid',
                        'type' => 'simple',
                        'searchOptions' => [
                            'operators' => ['ex'],
                        ],
                        'orderable' => true,
                    ],

                    // Strekkode
                    [
                        'key' => 'strekkode',
                        'type' => 'simple',
                        'orderable' => false,
                        'searchOptions' => [
                            'operators' => ['ex'],
                        ],
                        'orderable' => false,
                    ],

                    // Status
                    [
                        'key' => 'status',
                        'type' => 'autocomplete',
                        'orderable' => false,
                        'searchable' => 'advanced',
                    ],

                    // Avdeling
                    [
                        'key' => 'avdeling',
                        'type' => 'autocomplete',
                        'orderable' => false,
                    ],

                    // Samling
                    [
                        'key' => 'samling',
                        'type' => 'autocomplete',
                        'orderable' => false,
                    ],

                    // Hyllesignatur
                    [
                        'key' => 'hyllesignatur',
                        'type' => 'simple',
                        'orderable' => false,
                    ],

                    // Deponert
                    [
                        'key' => 'deponert',
                        'type' => 'simple',

                        'searchable' => 'advanced',
                        'orderable' => false,
                    ],

                    [
                        'key' => 'lokal_anmerkning',
                        'type' => 'simple',

                        'searchable' => 'advanced',
                        'orderable' => false,
                    ],

                    [
                        'key' => 'beholdning',
                        'type' => 'simple',

                        'searchable' => 'advanced',
                        'orderable' => false,
                    ],

                    [
                        'key' => 'utlaanstype',
                        'type' => 'simple',

                        'searchable' => 'advanced',
                        'orderable' => false,
                    ],

                    [
                        'key' => 'lisensbetingelser',
                        'type' => 'simple',

                        'searchable' => 'advanced',
                        'orderable' => false,
                    ],

                    [
                        'key' => 'tilleggsplassering',
                        'type' => 'simple',

                        'searchable' => 'advanced',
                        'orderable' => false,
                    ],

                    [
                        'key' => 'intern_bemerkning_aapen',
                        'type' => 'simple',

                        'searchable' => 'advanced',
                        'orderable' => false,
                    ],

                    // [
                    //     'key' => 'intern_bemerkning_lukket',
                    //     'type' => 'simple',

                    //     'searchable' => 'advanced',
                    // ],

                    [
                        'key' => 'bestillingstype',
                        'type' => 'simple',

                        'searchable' => 'advanced',
                        'orderable' => false,
                    ],

                    [
                        'key' => 'statusdato',
                        'type' => 'simple',

                        'searchable' => 'advanced',
                        'orderable' => false,
                    ],

                    [
                        'key' => 'seriedokid',
                        'type' => 'simple',
                        'searchOptions' => [
                            'operators' => ['ex'],
                        ],
                        'orderable' => false,
                    ],

                    [
                        'key' => 'har_hefter',
                        'type' => 'simple',

                        'searchable' => 'advanced',
                        'orderable' => false,
                    ],

                ],
            ],
        ],
    ];

    public function __construct()
    {
        $this->schemaOptions['autocompleteUrl'] = action('BibsysController@autocomplete');

        parent::__construct();
    }
}
