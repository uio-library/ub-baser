<?php

namespace App\Bibsys;

use App\Schema\Schema;

class BibsysSchema extends Schema
{
    public $prefix = 'bibsys';
    public $primaryId = 'dokid';
    public $costLimit = 20000;

    protected $schema = [
        'fields' => [
            // Søk i alle felt
            [
                'key' => 'any_field_ts',
                'type' => 'simple',

                'displayable' => false,
                'editable' => false,

                // ------------------------------
                /*
                 Note for the future:
                 When a text index search is combined with ORDER and LIMIT by some other field,
                 the text index is not utilized in Postgres 11. In Postgres 12, this seems to have
                 improved. Let's try again when USIT deploys Postgres 12.

                Fast:
                    select bibsys_search.* from bibsys_search
                    where plainto_tsquery('simple', '841106991') @@ any_field_ts
                    ORDER BY dokid DESC;

                Slow:

                    select bibsys_search.* from bibsys_search
                    where plainto_tsquery('simple', '841106991') @@ any_field_ts
                    ORDER BY dokid DESC LIMIT 10;

                */

                // 'searchable' => 'disabled',
                // ------------------------------

                'searchOptions' => [
                    'placeholder' => 'Du kan søke etter objektid, dokid, knyttid, avdeling, samling, tekst i MARC-posten, osv.',
                    'index' => ['type' => 'ts', 'ts_column' => 'any_field_ts'],
                    'operators' => ['eq', 'neq'],
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
                            'index' => ['column' => 'objektid', 'case' => Schema::LOWER_CASE],
                        ],
                        'orderable' => false,
                    ],

                    [
                        'key' => 'title_statement',
                        'type' => 'simple',
                        'orderable' => false,
                        'searchable' => 'disabled',
                    ],

                    [
                        'key' => 'pub_date',
                        'type' => 'simple',
                        'orderable' => false,
                        'searchable' => 'disabled',
                    ],

                    [
                        'key' => 'marc_record',
                        'type' => 'simple',
                        'orderable' => false,
                        'searchable' => 'disabled',
                    ],
                ],
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
                            'index' => ['column' => 'dokid', 'case' => Schema::LOWER_CASE],
                        ],
                        'orderable' => false,
                    ],

                    // Strekkode
                    [
                        'key' => 'strekkode',
                        'type' => 'simple',
                        'orderable' => false,
                        'searchOptions' => [
                            'operators' => ['ex'],
                            'index' => ['column' => 'strekkode', 'case' => Schema::LOWER_CASE],
                        ],
                        'orderable' => false,
                    ],

                    // Status
                    [
                        'key' => 'status',
                        'type' => 'simple',
                        'orderable' => false,
                        'searchable' => 'disabled',
                    ],

                    // Avdeling
                    [
                        'key' => 'avdeling',
                        'type' => 'simple',
                        'orderable' => false,
                        'searchOptions' => [
                            'operators' => ['ex'],
                            'placeholder' => 'Du kan høyretrunkere med *',
                            'index' => ['column' => 'lower(avdeling)', 'case' => Schema::LOWER_CASE],
                        ],
                    ],

                    // Samling
                    [
                        'key' => 'samling',
                        'type' => 'simple',
                        'orderable' => false,
                        'searchOptions' => [
                            'placeholder' => 'Du kan høyretrunkere med *',
                            'operators' => ['ex'],
                            'index' => ['column' => 'lower(samling)', 'case' => Schema::LOWER_CASE],
                        ],
                    ],

                    // Hyllesignatur
                    [
                        'key' => 'hyllesignatur',
                        'type' => 'simple',
                        'orderable' => false,
                        'searchOptions' => [
                            'placeholder' => 'Du kan høyretrunkere med *',
                            'operators' => ['ex'],
                            'index' => ['column' => 'lower(hyllesignatur)', 'case' => Schema::LOWER_CASE],
                        ],
                    ],

                    // Deponert
                    [
                        'key' => 'deponert',
                        'type' => 'simple',

                        'searchable' => 'disabled',
                        'orderable' => false,
                    ],

                    [
                        'key' => 'lokal_anmerkning',
                        'type' => 'simple',

                        'searchable' => 'disabled',
                        'orderable' => false,
                    ],

                    [
                        'key' => 'beholdning',
                        'type' => 'simple',

                        'searchable' => 'disabled',
                        'orderable' => false,
                    ],

                    [
                        'key' => 'utlaanstype',
                        'type' => 'simple',

                        'searchable' => 'disabled',
                        'orderable' => false,
                    ],

                    [
                        'key' => 'lisensbetingelser',
                        'type' => 'simple',

                        'searchable' => 'disabled',
                        'orderable' => false,
                    ],

                    [
                        'key' => 'tilleggsplassering',
                        'type' => 'simple',

                        'searchable' => 'disabled',
                        'orderable' => false,
                    ],

                    [
                        'key' => 'intern_bemerkning_aapen',
                        'type' => 'simple',

                        'searchable' => 'disabled',
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

                        'searchable' => 'disabled',
                        'orderable' => false,
                    ],

                    [
                        'key' => 'statusdato',
                        'type' => 'simple',

                        'searchable' => 'disabled',
                        'orderable' => false,
                    ],

                    [
                        'key' => 'seriedokid',
                        'type' => 'simple',
                        'searchOptions' => [
                            'operators' => ['ex'],
                            'index' => ['column' => 'seriedokid', 'case' => Schema::LOWER_CASE],
                        ],
                        'orderable' => false,
                    ],

                    [
                        'key' => 'har_hefter',
                        'type' => 'simple',

                        'searchable' => 'disabled',
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
