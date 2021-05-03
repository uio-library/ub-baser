<?php

namespace App\Bases\Opes;

use App\Schema\EntitiesField;
use App\Schema\Operators;
use App\Schema\Schema as BaseSchema;

class Schema extends BaseSchema
{
    protected $schema = [
        'id' => 'opes',
        'fields' => [

            // ----

            // ID
            [
                'key' => 'id',
                'type' => 'incrementing',
                'showInRecordView' => true,
            ],
            // standard designation
            [
                'key' => 'standard_designation',
                'type' => 'simple',
                'edit' => false,
                'search' => [
                    'sort_index' => 'standard_designation_sort',
                ],
            ],
            // inventary number
            [
                'key' => 'inv_no',
                'type' => 'simple',
            ],
            // P.Oslo volume - part of standard_designation
            [
                'key' => 'p_oslo_vol',
                'type' => 'simple',
                'search' => false,
                'showInTableView' => false,
                'showInRecordView' => false,
                'edit' => [
                    'placeholder' => '',
                    'help' => 'Volume number in the P.Oslo series, entered as a Roman numeral.',
                ],

            ],
            // P.Oslo no. - part of standard_designation
            [
                'key' => 'p_oslo_nr',
                'type' => 'simple',
                'search' => false,
                'showInTableView' => false,
                'showInRecordView' => false,
                'edit' => [
                    'placeholder' => '',
                    'help' => 'Page number in the P.Oslo series.',
                ],
            ],
            // Trismegistos URL (Added Jan 2021)
            [
                'key' => 'trismegistos_url',
                'type' => 'simple', // URL?
                'search' => false,
                'showInTableView' => false,
                'showInRecordView' => false,
            ],
            // Papyri.info/DCLP-URL (Added Jan 2021)
            [
                'key' => 'papyri_dclp_url',
                'type' => 'simple', // URL?
                'search' => false,
                'showInTableView' => false,
                'showInRecordView' => false,
            ],

            // ----

            // Søk i alle felt
            [
                'key' => 'q',
                'type' => 'search_only',

                'search' => [
                    'widget' => 'simple',
                    'type' => 'ts',
                    'ts_index' => 'any_field_ts',
                    'operators' => [
                        Operators::CONTAINS,
                        Operators::NOT_CONTAINS,
                    ],
                ],
            ],
            // Opprettet
            [
                'key' => 'created_at',
                'datatype' => 'date',
                'type' => 'simple',
                'search' => [
                    'advanced' => true,
                    'placeholder' => 'YYYY-MM-DD',
                    'operators' => [
                        Operators::EQUALS,
                        Operators::GTE,
                        Operators::LTE,
                    ],
                ],
                'edit' => false,
                'columnClassName' => 'dt-body-nowrap',
            ],
            // Sist endret
            [
                'key' => 'updated_at',
                'datatype' => 'date',
                'type' => 'simple',
                'search' => [
                    'advanced' => true,
                    'placeholder' => 'YYYY-MM-DD',
                    'operators' => [
                        Operators::EQUALS,
                        Operators::GTE,
                        Operators::LTE,
                    ],
                ],
                'edit' => false,
                'columnClassName' => 'dt-body-nowrap',
            ],
        ],
        'groups' => [
            [
                'key' => 'background_and_physical',
                'fields' => [
                    // Material
                    [
                        'key' => 'material_long',
                        'type' => 'select',
                        'multiple' => false,
                        'defaultValue' => ['Papyrus'],
                        'search' => [
                            'type' => 'array',
                            'widget' => 'autocomplete',
                            'advanced' => true,
                            'placeholder' => '',
                        ],
                        'edit' => [
                            'preload' => true,
                            'allow_new_values' => true,
                            'placeholder' => '',
                        ],
                    ],

                    // Connections
                    [
                        'key' => 'connections',
                        'type' => 'simple',
                        'search' => [
                            'advanced' => true,
                        ],
                    ],
                    // Size
                    [
                        'key' => 'size',
                        'type' => 'simple',
                        'search' => [
                            'advanced' => true,
                        ],
                    ],
                    // Lines
                    [
                        'key' => 'lines',
                        'type' => 'simple',
                        'search' => [
                            'advanced' => true,
                        ],
                    ],
                    // Publication side
                    [
                        'key' => 'publ_side',
                        'type' => 'select',
                        'multiple' => false,
                        'defaultValue' => [],
                        'search' => [
                            'type' => 'array',
                            'widget' => 'autocomplete',
                            'advanced' => true,
                            'placeholder' => '',
                        ],
                        'edit' => [
                            'preload' => true,
                            'allow_new_values' => true,
                            'placeholder' => '',
                        ],
                    ],
                    // Palaeogrfic description
                    [
                        'key' => 'palaeographic_description',
                        'type' => 'simple',
                        'multiline' => true,
                    ],
                    // Conservation status
                    [
                        'key' => 'conservation_status',
                        'type' => 'simple',
                        'multiline' => true,
                    ],
                ],
            ],
            [
                'key' => 'content',
                'fields' => [
                    // Date
                    [
                        'key' => 'date',
                        'type' => 'simple',
                        'edit' => [
                            'placeholder' => '',
                            'help' => 'Date or estimated period of creation.',
                        ],
                    ],
                    // Date1
                    [
                        'key' => 'date1',
                        'type' => 'simple',
                        'search' => false,
                        'showInTableView' => false,
                        'showInRecordView' => false,
                        'edit' => [
                            'placeholder' => '',
                            'help' => 'Numerical year that marks the beginning of the date or estimated period of' .
                                ' creation specified in the "Date field". Used for sorting and filtering. Example:' .
                                '"250" when the estimated period of creation is "IInd half of IIIrd century A.D."',
                        ],
                    ],
                    // Date2
                    [
                        'key' => 'date2',
                        'type' => 'simple',
                        'search' => false,
                        'showInTableView' => false,
                        'showInRecordView' => false,
                        'edit' => [
                            'placeholder' => '',
                            'help' => 'Numerical year that marks the end of the date or estimated period of' .
                                ' creation specified in the "Date field". Used for sorting and filtering. Example:' .
                                '"299" when the estimated period of creation is "IInd half of IIIrd century A.D."',
                        ],
                    ],
                    // Origin
                    [
                        'key' => 'origin',
                        'type' => 'autocomplete',
                    ],
                    // Language
                    [
                        'key' => 'language',
                        'type' => 'select',
                        'multiple' => false,
                        'defaultValue' => [],
                        'search' => [
                            'type' => 'array',
                            'widget' => 'autocomplete',
                            'advanced' => true,
                            'placeholder' => '',
                        ],
                        'edit' => [
                            'preload' => true,
                            'allow_new_values' => true,
                            'placeholder' => '',
                        ],
                    ],
                    // Genre
                    [
                        'key' => 'genre',
                        'type' => 'select',
                        'multiple' => false,
                        'defaultValue' => [],
                        'search' => [
                            'type' => 'array',
                            'widget' => 'autocomplete',
                            'advanced' => true,
                            'placeholder' => '',
                        ],
                        'edit' => [
                            'preload' => true,
                            'allow_new_values' => true,
                            'placeholder' => '',
                        ],
                    ],
                    // Author
                    [
                        'key' =>  'author',
                        'type' =>  'autocomplete',
                    ],
                    // Title or type of text
                    [
                        'key' =>  'title_or_type',
                        'type' =>  'autocomplete',
                    ],
                    // Content
                    [
                        'key' =>  'content',
                        'type' =>  'simple',
                        'multiline' => true,
                    ],
                    // Subject Headings
                    [
                        'key' =>  'subjects',
                        'type' =>  'select',
                        'defaultValue' => [],
                        'multiple' => true,
                        'search' => [
                            'widget' => 'autocomplete',
                            'type' => 'array',
                        ],
                        'edit' => [
                            'preload' => true,
                            'allow_new_values' => true,
                        ],
                    ],
                    // Named people
                    [
                        'key' =>  'people',
                        'type' =>  'select',
                        'defaultValue' => [],
                        'multiple' => true,
                        'search' => [
                            'widget' => 'autocomplete',
                            'type' => 'array',
                        ],
                        'edit' => [
                            'preload' => true,
                            'allow_new_values' => true,
                        ],
                    ],
                    // Named places
                    [
                        'key' =>  'places',
                        'type' =>  'select',
                        'defaultValue' => [],
                        'multiple' => true,
                        'search' => [
                            'widget' => 'autocomplete',
                            'type' => 'array',
                        ],
                        'edit' => [
                            'preload' => true,
                            'allow_new_values' => true,
                        ],
                    ],
                    // Translation
                    [
                        'key' =>  'translation',
                        'type' =>  'simple',
                        'multiline' => true,
                    ],
                    //Provenance
                    [
                        'key' => 'provenance',
                        'type' => 'autocomplete',
                    ],
                    //Acquisition
                    [
                        'key' => 'acquisition',
                        'type' => 'simple',
                    ],
                    // Acquisition year (Added Jan 2021)
                    [
                        'key' => 'acquisition_year',
                        'type' => 'simple', // TODO: int
                    ],
                    // Internal comments (Added Jan 2021)
                    [
                        'key' => 'internal_comments',
                        'type' => 'simple',
                        // Midlertidig løsning. Ønsker egentlig at de skal vises bare for innloggede
                        'search' => false,
                        'showInTableView' => false,
                        'showInRecordView' => false,
                    ],
                    // Conservation notes (Added Jan 2021)
                    [
                        'key' => 'conservation_notes',
                        'type' => 'simple',
                        'multiline' => true,
                        // Midlertidig løsning. Ønsker egentlig at de skal vises bare for innloggede
                        'search' => false,
                        'showInTableView' => false,
                        'showInRecordView' => false,
                    ],

                    // Bibliography
                    [
                        'key' =>  'bibliography',
                        'type' => 'simple', // TODO: Ikke helt riktig, burde kanskje ha en egen 'list'-type?
                        'multiline' => true,
                        'showInRecordView' => false, // Vises i eget avsnitt
                        'edit' => [
                            'placeholder' => '',
                            'help' => 'Multiple corrections should be separated by semicolon followed by space.',
                        ],
                    ],
                ],
            ],

            [
                'key' => 'editions',
                'searchable' => false,
                'fields' => [

                    // Editions
                    [
                        'key' => 'editions',
                        'type' => 'entities',
                        'entityType' => Edition::class,
                        'entitySchema' => EditionSchema::class,
                        'entityRelation' => EntitiesField::ONE_TO_MANY_RELATION,
                        'modelAttribute' => 'editions',
                        'relatedPivotKey' => 'opes_id',
                        'pivotFields' => [],
                        'search' => false,
                        'showInTableView' => false,
                    ],

                ],
            ],

            [
                // we need to add a text line creating a link the imageserver
                // if possible not the link but a text to click
                // like Recto Verso. to be done later
                'key' => 'images',
                'searchable' => false,
                'fields' => [
                    // Image Recto
                    [
                        // print "https://ub-media.uio.no/OPES/jpg/"
                        'key' => 'fullsizefront_r1',
                        'type' => 'simple',
                        'columnClassName' => 'p-1 align-middle text-center',
                        // print ".jpg";
                    ],
                    // Image Verso
                    [
                        // print "https://ub-media.uio.no/OPES/jpg/"
                        'key' => 'fullsizeback_r1',
                        'type' => 'simple',
                        'columnClassName' => 'p-1 align-middle text-center',
                        // print ".jpg";
                    ],
                ],
            ],

        ],
    ];
}
