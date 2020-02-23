<?php

namespace App\Bases\Opes;

use App\Schema\Operators;
use App\Schema\Schema as BaseSchema;

class Schema extends BaseSchema
{
    protected $schema = [
        "id" => "opes",
        "fields" => [
            // ID
            [
                "key" => "id",
                "type" => "incrementing",
                "displayable" => true,
            ],
            // inventary number
            [
                "key" => "inv_no",
                "type" => "simple",
            ],
            // Søk i alle felt
            [
                "key" => "q",
                "type" => "simple",

                "displayable" => false,
                "edit" => false,

                "search" => [
                    "type" => "ts",
                    "ts_index" => "any_field_ts",
                    "operators" => [
                        Operators::CONTAINS,
                        Operators::NOT_CONTAINS
                    ],
                ],
            ],
            // Opprettet
            [
                "key" => "created_at",
                "type" => "simple",
                "edit" => false,
                "search" => false,
                "columnClassName" => "dt-body-nowrap",
            ],
            // Sist endret
            [
                "key" => "updated_at",
                "type" => "simple",
                "edit" => false,
                "search" => false,
                "columnClassName" => "dt-body-nowrap",
            ],
        ],
        "groups" => [
            [
                "label" => "Background and Physical Properties",
                "fields" => [
                    // Material
                    [
                        "key" => "material_long",
                        "type" => "select",
                    ],
                    // Material long
                    //[
                    //    "key" =>  "material_long",
                    //    "type" =>  "simple",
                    //
                    //],
                    // Connections
                    [
                        "key" => "connections",
                        "type" => "simple",
                        "search" => [
                            "advanced" => true,
                        ],
                    ],
                    // Size
                    [
                        "key" => "size",
                        "type" => "simple",
                        "search" => [
                            "advanced" => true,
                        ],
                    ],
                    // Lines
                    [
                        "key" => "lines",
                        "type" => "simple",
                        "search" => [
                            "advanced" => true,
                        ],
                    ],
                    // Publication side
                    [
                        "key" => "publ_side",
                        "type" => "select",
                    ],
                    // Palaeogrfic description
                    [
                        "key" => "palaeographic_description",
                        "type" => "simple",
                    ],
                ],
            ],
            [
                "label" => "Contents",
                "fields" => [
                    // Date
                    [
                        "key" => "date",
                        "type" => "simple",
                    ],
                    // Origin
                    [
                        "key" => "origin",
                        "type" => "autocomplete",
                    ],
                    // Language
                    // [
                    //     "key" =>  "language",
                    //     "type" =>  "simple",
                    // ],
                    // Genre
                    [
                        "key" =>  "genre",
                        "type" =>  "autocomplete",
                    ],
                    // Author
                    [
                        "key" =>  "author",
                        "type" =>  "autocomplete",
                    ],
                    // Title or type of text
                    [
                        "key" =>  "title_or_type",
                        "type" =>  "autocomplete",
                    ],
                    // Content
                    [
                        "key" =>  "content",
                        "type" =>  "simple",
                    ],
                    // Subject Headings
                    [
                        "key" =>  "subj_headings",
                        "type" =>  "select",
                        "defaultValue" => [],
                        "multiple" => true,
                        "search" => [
                            "widget" => "autocomplete",
                            "type" => "array",
                        ],
                        "edit" => [
                            "preload" => true,
                            "allow_new_values" => true,
                        ],
                    ],
                    // Persons
                    [
                        "key" =>  "persons",
                        "type" =>  "select",
                        "defaultValue" => [],
                        "multiple" => true,
                        "search" => [
                            "widget" => "autocomplete",
                            "type" => "array",
                        ],
                        "edit" => [
                            "preload" => true,
                            "allow_new_values" => true,
                        ],
                    ],
                    // Geographica
                    [
                        "key" =>  "geographica",
                        "type" =>  "autocomplete",
                    ],
                    // Translation
                    [
                        "key" =>  "translation",
                        "type" =>  "simple",
                    ],
                    //Provenance
                    [
                        "key" => "provenance",
                        "type" => "autocomplete",
                    ],
                    //Acquisition
                    [
                        "key" => "acquisition",
                        "type" => "simple",
                    ],

                ],
            ],

            [
                "label" => "Information on publication",
                "search" => false,
                "fields" => [

                    // WAIT with this:
                    //        [
                    //            "key" =>  "Year",
                    //            "type" => "simple",
                    //        ],
                    //       [
                    //            "key" =>  "Pg_no",
                    //            "type" => "simple",
                    //        ],
                    //       [
                    //            "key" =>  "photo",
                    //            "type" => "simple",
                    //        ],
                    //       [
                    //            "key" =>  "sb",
                    //            "type" => "simple",
                    //        ],
                    //       [
                    //            "key" =>  "corrections",
                    //            "type" => "simple",
                    //        ],
                    //       [
                    //            "key" =>  "preferred_citation",
                    //            "type" => "simple",
                    //        ],
                    //       [
                    //            "key" =>  "ddbdp_pmichcitation",
                    //            "type" => "simple",
                    //        ],
                    //       [
                    //             "key" => "ddbdp_omichcitation",
                    //            "type" => "simple",
                    //        ],
                    //       [
                    //            "key" =>  "ddbdp_p_rep",
                    //            "type" => "simple",
                    //        ],
                    //       [
                    //            "key" =>  "ddbdp_o_rep",
                    //            "type" => "simple",
                    //        ],
                    //      [
                    //            "key" => "ser_vol",
                    //            "type" => "simple",
                    //        ],
                    //      [
                    //            "key" => "editor",
                    //            "type" => "simple",
                    //        ],

                    // republication
                    [
                        "key" =>  "rep_ser_old",
                        "type" =>  "simple",
                    ],
                    // republication
                    [
                        "key" =>  "rep_pg_no_old",
                        "type" =>  "simple",
                    ],
                    // Further republication
                    [
                        "key" =>  "further_rep",
                        "type" =>  "simple",
                    ],
                    // Further republication notes
                    [
                        "key" =>  "further_replication_note",
                        "type" =>  "simple",
                    ],
                    // Bibliography
                    [
                        "key" =>  "bibliography",
                        "type" =>  "simple",
                    ],

                ],
            ],

            [
                // we need to add a text line creating a link the imageserver
                // if possible not the link but a text to click
                // like Recto Verso. to be done later
                "label" => "Images",
                "search" => false,
                "fields" => [
                    // Image Recto
                    [
                        // print "https://ub-media.uio.no/OPES/jpg/"
                        "key" => "fullsizefront_r1",
                        "type" => "simple",
                        "columnClassName" => "p-1 align-middle text-center",
                        // print ".jpg";
                    ],
                    // Image Verso
                    [
                        // print "https://ub-media.uio.no/OPES/jpg/"
                        "key" => "fullsizeback_r1",
                        "type" => "simple",
                        "columnClassName" => "p-1 align-middle text-center",
                        // print ".jpg";
                    ],
                ],
            ],

        ],
    ];
}
