<?php

namespace App\Litteraturkritikk;

use App\BaseSchema;

class LitteraturkritikkSchema extends BaseSchema
{
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

            // Søk i alle felt
            [
                'key' => 'q',
                'type' => 'simple',
                'display' => false,
                'edit' => false,
                'search' => [
                    'advanced' => false,
                    'placeholder' => 'Forfatter, kritiker, ord i tittel, kommentar, etc... Avslutt med * om du føler for å trunkere.',
                    'index' => ['type' => 'ts', 'ts_column' => 'any_field_ts'],
                    'operators' => ['eq', 'neq']
                ]
            ],

            // Person-søk (forfatter eller kritiker)
            [
                'key' => 'person',
                'type' => 'autocomplete',
                'display' => false,
                'edit' => false,
                'search' => [
                    'advanced' => false,
                    'placeholder' => 'Fornavn og/eller etternavn',
                    'index' => ['type' => 'ts', 'ts_column' => 'person_ts'],
                    'operators' => ['eq', 'neq'],
                ]
            ],
        ],

        'groups' => [

            // Verket
            [
                'label' => 'Verket',
                'fields' => [

                    // Tittel
                    [
                        'key' => 'verk_tittel',
                        'type' => 'autocomplete',
                        'search' => [
                            'placeholder' => 'Tittel på omtalt verk',
                            'options' => [],
                            'index' => [
                                'type' => 'ts',
                                'column' => 'verk_tittel',
                                'ts_column' => 'verk_tittel_ts',
                            ],
                        ],
                    ],

                    // Forfatter
                    [
                        'key' => 'verk_forfatter',
                        'type' => 'persons',
                        'model_attribute' => 'forfattere',
                        'person_role' => 'forfatter',
                        'search' => [
                            'type' => 'autocomplete',
                            'placeholder' => 'Fornavn og/eller etternavn',
                            'index' => [
                                'type' => 'ts',
                                'column' => 'verk_forfatter',
                                'ts_column' => 'forfatter_ts',
                            ],
                            'operators' => [
                                'eq',
                                'neq',
                                'isnull',
                                'notnull',
                            ],
                        ],
                    ],

                    // mfl.
                    [
                        'key' => 'verk_forfatter_mfl',
                        'type' => 'boolean',
                        'help' => 'Kryss av hvis det er flere personer som ikke er listet opp',
                        'default' => false,
                        'display' => false,
                        'search' => false,
                    ],

                    // År
                    [
                        'key' => 'verk_dato',
                        'type' => 'simple',
                        'display' => [
                            'columnClassName' => 'dt-body-nowrap',
                        ],
                        'search' => [
                            'type' => 'rangeslider',
                            'advanced' => true,
                            'index' => [
                                'type' => 'range',
                                'column' => 'verk_dato',
                            ],
                        ],
                    ],

                    // Sjanger
                    [
                        'key' => 'verk_sjanger',
                        'type' => 'autocomplete',
                        'search' => [
                            'placeholder' => 'Sjanger til det omtalte verket. F.eks. lyrikk, roman, ...',
                            'options' => [],
                            'index' => ['type' => 'simple', 'column' => 'verk_sjanger'],
                        ],
                    ],

                    // Språk
                    [
                        'key' => 'verk_spraak',
                        'type' => 'autocomplete',
                        'search' => [
                            'advanced' => true,
                        ],
                    ],

                    // Kommentar
                    [
                        'key' => 'verk_kommentar',
                        'type' => 'simple',
                        'search' => [
                            'advanced' => true,
                        ],
                    ],

                    // Utgivelsessted
                    [
                        'key' => 'verk_utgivelsessted',
                        'type' => 'autocomplete',
                        'search' => [
                            'advanced' => true,
                        ],
                    ],

                ],
            ],

            // Kritikken
            [
                'label' => 'Kritikken',
                'fields' => [

                    // Kritiker
                    [
                        'key' => 'kritiker',
                        'type' => 'persons',
                        'model_attribute' => 'kritikere',
                        'person_role' => 'kritiker',
                        'search' => [
                            'type' => 'autocomplete',
                            'placeholder' => 'Fornavn og/eller etternavn',
                            'options' => [],
                            'index' => [
                                'type' => 'ts',
                                'column' => 'kritiker',
                                'ts_column' => 'kritiker_ts',
                            ],
                        ],
                    ],

                    // mfl.
                    [
                        'key' => 'kritiker_mfl',
                        'type' => 'boolean',
                        'help' => 'Kryss av hvis det er flere personer som ikke er listet opp',
                        'default' => false,
                        'display' => false,
                        'search' => false,
                    ],

                    // Publikasjon
                    [
                        'key' => 'publikasjon',
                        'type' => 'autocomplete',
                        'search' => [
                            'placeholder' => 'Publikasjon',
                            'index' => [
                                'type' => 'simple',
                                'column' => 'publikasjon',
                            ],
                        ],
                    ],

                    // Type
                    [
                        'key' => 'kritikktype',
                        'type' => 'tags',
                        'default' => [],
                        'search' => [
                            'type' => 'autocomplete',
                            'placeholder' => 'F.eks. teaterkritikk, forfatterportrett, ...',
                            'index' => [
                                'type' => 'array',
                                'column' => 'kritikktype',
                            ],
                        ],
                    ],

                    // År
                    [
                        'key' => 'dato',
                        'type' => 'simple',
                        'display' => [
                            'columnClassName' => 'dt-body-nowrap',
                        ],
                        'search' => [
                            'type' => 'rangeslider',
                            'index' => [
                                'type' => 'range',
                                'column' => 'aar_numeric',
                            ],
                        ],
                    ],

                    // Språk
                    [
                        'key' => 'spraak',
                        'type' => 'autocomplete',
                        'search' => [
                            'advanced' => true,
                        ],
                    ],

                    // Tittel
                    [
                        'key' => 'tittel',
                        'type' => 'simple',
                        'search' => [
                            'advanced' => true,
                        ],
                    ],

                    [
                        'key' => 'utgivelsessted',
                        'type' => 'autocomplete',
                        'search' => [
                            'advanced' => true,
                        ],
                    ],
                    [
                        'key' => 'aargang',
                        'type' => 'simple',
                    ],
                    [
                        'key' => 'bind',
                        'type' => 'simple',
                    ],
                    [
                        'key' => 'hefte',
                        'type' => 'simple',
                    ],
                    [
                        'key' => 'nummer',
                        'type' => 'simple',
                    ],
                    [
                        'key' => 'sidetall',
                        'type' => 'simple',
                    ],
                    [
                        'key' => 'kommentar',
                        'type' => 'simple',
                        'search' => [
                            'advanced' => true,
                        ],
                    ],
                    [
                        'key' => 'utgivelseskommentar',
                        'type' => 'simple',
                        'search' => [
                            'advanced' => true,
                        ],
                    ],
                    [
                        'key' => 'fulltekst_url',
                        'type' => 'url',
                        'search' => [
                            'advanced' => true,
                        ],
                    ],
                ],
            ],
        ],
    ];

    public function __construct()
    {
        $this->init([
            'autocompleTarget' => action('LitteraturkritikkController@autocomplete'),
            'minYear' => 1789,
            'maxYear' => (int) strftime('%Y'),
        ]);
    }
}