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
            // Title or type of text
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
                    ],
                    // Size
                    [
                        'key' => 'size',
                        'type' => 'simple',
                    ],
                    // Lines
                    [
                        'key' => 'lines',
                        'type' => 'simple',
                    ],
                    // Publication side
                    [
                        'key' => 'publ_side',
                        'type' => 'simple',
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
                        'type' => 'simple',
                    ],
                    //Provenance
                    [
                        'key' => 'provenance',
                        'type' => 'simple',
                    ],
                    //Acquisition
                    [
                        'key' => 'acquisition',
                        'type' => 'simple',
                    ],
                    // Language
                    [
                        'key' =>  'language',
                        'type' =>  'simple',
                    ],
                    // Genre
                    [
                        'key' =>  'genre',
                        'type' =>  'simple',
                    ],
                    // Author
                    [
                        'key' =>  'author',
                        'type' =>  'simple',
                    ],
                    // Title or type of text
                    [
                        'key' =>  'title_or_type',
                        'type' =>  'simple',
                    ],
                    // Content
                    [
                        'key' =>  'content',
                        'type' =>  'simple',
                    ],
                    // Subject Headings
                    [
                        'key' =>  'subj_headings',
                        'type' =>  'simple',
                    ],
                    // Persons
                    [
                        'key' =>  'persons',
                        'type' =>  'simple',
                    ],
                    // Geographica
                    [
                        'key' =>  'geographica',
                        'type' =>  'simple',
                    ],
                    // Translation
                    [
                        'key' =>  'translation',
                        'type' =>  'simple',
                    ],

                ],
            ],

            [
                'label' => 'Information on publication',
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
                'fields' => [
                    // Image Recto
                    [
                        // print "https://ub-media.uio.no/OPES/jpg/"
                        'key' => 'fullsizefront_r1',
                        'type' => 'simple',
                        // print ".jpg";
                    ],
                    // Image Verso
                    [
                        // print "https://ub-media.uio.no/OPES/jpg/"
                        'key' => 'fullsizeback_r1',
                        'type' => 'simple',
                        // print ".jpg";
                    ],
                ],
            ],

        ],
    ];
}
