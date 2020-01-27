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
            // inventary number
            [
                'key' => 'inv_no',
                'type' => 'simple',
            ],
            // Søk i alle felt
            [
                'key' => 'q',
                'type' => 'simple',

                'displayable' => false,
                'editable' => false,
                'searchable' => 'simple',

                'searchOptions' => [
                    'placeholder' => '',
                    'index' => ['type' => 'ts', 'ts_column' => 'any_field_ts'],
                    'operators' => ['eq', 'neq'],
                ],
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
        ],
        'groups' => [
            [
                'label' => 'Background and Physical Properties',
                'fields' => [
                    // Material
                    [
                        'key' => 'material_long',
                        'type' => 'select',
                    ],
                    // Material long
                    //[
                    //    'key' =>  'material_long',
                    //    'type' =>  'simple',
                    //
                    //],
                    // Connections
                    [
                        'key' => 'connections',
                        'type' => 'simple',
                        'searchable' => 'advanced',
                    ],
                    // Size
                    [
                        'key' => 'size',
                        'type' => 'simple',
                        'searchable' => 'advanced',
                    ],
                    // Lines
                    [
                        'key' => 'lines',
                        'type' => 'simple',
                        'searchable' => 'advanced',
                    ],
                    // Publication side
                    [
                        'key' => 'publ_side',
                        'type' => 'select',
                    ],
                    // Palaeogrfic description
                    [
                        'key' => 'palaeographic_description',
                        'type' => 'simple',
                    ],
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
                        'type' => 'autocomplete',
                    ],
                    // Language
                    // [
                    //     'key' =>  'language',
                    //     'type' =>  'simple',
                    // ],
                    // Genre
                    [
                        'key' =>  'genre',
                        'type' =>  'autocomplete',
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
                    ],
                    // Subject Headings
                    [
                        'key' =>  'subj_headings',
                        'type' =>  'tags',
                        'searchOptions' => [
                            'type' => 'autocomplete',
                            'placeholder' => '',
                            'index' => [
                                'type' => 'array',
                                'column' => 'subj_headings',
                            ],
                        ],
                    ],
                    // Persons
                    [
                        'key' =>  'persons',
                        'type' =>  'tags',
                        'searchOptions' => [
                            'type' => 'autocomplete',
                            'placeholder' => '',
                            'index' => [
                                'type' => 'array',
                                'column' => 'persons',
                            ],
                        ],
                    ],
                    // Geographica
                    [
                        'key' =>  'geographica',
                        'type' =>  'autocomplete',
                    ],
                    // Translation
                    [
                        'key' =>  'translation',
                        'type' =>  'simple',
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

                ],
            ],

            [
                'label' => 'Information on publication',
                'searchable' => 'disabled',
                'fields' => [

                    // WAIT with this:
                    //        [
                    //            'key' =>  'Year',
                    //            'type' => 'simple',
                    //        ],
                    //       [
                    //            'key' =>  'Pg_no',
                    //            'type' => 'simple',
                    //        ],
                    //       [
                    //            'key' =>  'photo',
                    //            'type' => 'simple',
                    //        ],
                    //       [
                    //            'key' =>  'sb',
                    //            'type' => 'simple',
                    //        ],
                    //       [
                    //            'key' =>  'corrections',
                    //            'type' => 'simple',
                    //        ],
                    //       [
                    //            'key' =>  'preferred_citation',
                    //            'type' => 'simple',
                    //        ],
                    //       [
                    //            'key' =>  'ddbdp_pmichcitation',
                    //            'type' => 'simple',
                    //        ],
                    //       [
                    //             'key' => 'ddbdp_omichcitation',
                    //            'type' => 'simple',
                    //        ],
                    //       [
                    //            'key' =>  'ddbdp_p_rep',
                    //            'type' => 'simple',
                    //        ],
                    //       [
                    //            'key' =>  'ddbdp_o_rep',
                    //            'type' => 'simple',
                    //        ],
                    //      [
                    //            'key' => 'ser_vol',
                    //            'type' => 'simple',
                    //        ],
                    //      [
                    //            'key' => 'editor',
                    //            'type' => 'simple',
                    //        ],

                    // republication
                    [
                        'key' =>  'rep_ser_old',
                        'type' =>  'simple',
                    ],
                    // republication
                    [
                        'key' =>  'rep_pg_no_old',
                        'type' =>  'simple',
                    ],
                    // Further republication
                    [
                        'key' =>  'further_rep',
                        'type' =>  'simple',
                    ],
                    // Further republication notes
                    [
                        'key' =>  'further_replication_note',
                        'type' =>  'simple',
                    ],
                    // Bibliography
                    [
                        'key' =>  'bibliography',
                        'type' =>  'simple',
                    ],

                ],
            ],

            [
                // we need to add a text line creating a link the imageserver
                // if possible not the link but a text to click
                // like Recto Verso. to be done later
                'label' => 'Images',
                'searchable' => 'disabled',
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
