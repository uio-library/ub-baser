<?php

namespace App\Bases\Bibsys;

use App\Schema\Operators;
use App\Schema\Schema as BaseSchema;

class Schema extends BaseSchema
{
    public $primaryId = "dokid";

    protected $schema = [
        "id" => "bibsys",
        "fields" => [
            // Søk i alle felt
            [
                "key" => "any_field_ts",
                "type" => "simple",

                "displayable" => false,
                "edit" => false,

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

                // "search" => false,
                // ------------------------------

                "search" => [
                    "placeholder" => "Du kan søke etter objektid, dokid, knyttid, avdeling, samling, tekst i MARC-posten, osv.",
                    "type" => "ts",
                    "ts_index" => "any_field_ts",
                    "operators" => [
                        Operators::CONTAINS,
                        Operators::NOT_CONTAINS,
                    ],
                ],
            ],
        ],

        "groups" => [
            [
                "key" => "objektpost",
                "fields" => [

                    [
                        "key" => "objektid",
                        "type" => "simple",

                        "search" => [
                            "operators" => [
                                Operators::EQUALS,
                            ],
                            "case" => "LOWER_CASE",
                        ],
                        "orderable" => false,
                    ],

                    [
                        "key" => "title_statement",
                        "type" => "simple",
                        "orderable" => false,
                        "search" => false,
                    ],

                    [
                        "key" => "pub_date",
                        "type" => "simple",
                        "orderable" => false,
                        "search" => false,
                    ],

                    [
                        "key" => "marc_record",
                        "type" => "simple",
                        "orderable" => false,
                        "search" => false,
                    ],
                ],
            ],
            [
                "key" => "dokumentpost",
                "fields" => [

                    // Dokid
                    [
                        "key" => "dokid",
                        "type" => "simple",
                        "search" => [
                            "operators" => [
                                Operators::EQUALS,
                            ],
                            "case" => "LOWER_CASE",
                        ],
                        "orderable" => false,
                    ],

                    // Strekkode
                    [
                        "key" => "strekkode",
                        "type" => "simple",
                        "search" => [
                            "operators" => [
                                Operators::EQUALS,
                            ],
                            "case" => "LOWER_CASE",
                        ],
                        "orderable" => false,
                    ],

                    // Status
                    [
                        "key" => "status",
                        "type" => "simple",
                        "orderable" => false,
                        "search" => false,
                    ],

                    // Avdeling
                    [
                        "key" => "avdeling",
                        "type" => "simple",
                        "orderable" => false,
                        "search" => [
                            "operators" => [
                                Operators::EQUALS,
                            ],
                            "placeholder" => "Du kan høyretrunkere med *",
                            "index" => "lower(avdeling)",
                            "case" => "LOWER_CASE",
                        ],
                    ],

                    // Samling
                    [
                        "key" => "samling",
                        "type" => "simple",
                        "orderable" => false,
                        "search" => [
                            "placeholder" => "Du kan høyretrunkere med *",
                            "operators" => [
                                Operators::EQUALS,
                            ],
                            "index" => "lower(samling)",
                            "case" => "LOWER_CASE",
                        ],
                    ],

                    // Hyllesignatur
                    [
                        "key" => "hyllesignatur",
                        "type" => "simple",
                        "orderable" => false,
                        "search" => [
                            "placeholder" => "Du kan høyretrunkere med *",
                            "operators" => [
                                Operators::EQUALS,
                            ],
                            "index" => "lower(hyllesignatur)",
                            "case" => "LOWER_CASE",
                        ],
                    ],

                    // Deponert
                    [
                        "key" => "deponert",
                        "type" => "simple",

                        "search" => false,
                        "orderable" => false,
                    ],

                    [
                        "key" => "lokal_anmerkning",
                        "type" => "simple",

                        "search" => false,
                        "orderable" => false,
                    ],

                    [
                        "key" => "beholdning",
                        "type" => "simple",

                        "search" => false,
                        "orderable" => false,
                    ],

                    [
                        "key" => "utlaanstype",
                        "type" => "simple",

                        "search" => false,
                        "orderable" => false,
                    ],

                    [
                        "key" => "lisensbetingelser",
                        "type" => "simple",

                        "search" => false,
                        "orderable" => false,
                    ],

                    [
                        "key" => "tilleggsplassering",
                        "type" => "simple",

                        "search" => false,
                        "orderable" => false,
                    ],

                    [
                        "key" => "intern_bemerkning_aapen",
                        "type" => "simple",

                        "search" => false,
                        "orderable" => false,
                    ],

                    // [
                    //     "key" => "intern_bemerkning_lukket",
                    //     "type" => "simple",

                    //     "search" => "advanced",
                    // ],

                    [
                        "key" => "bestillingstype",
                        "type" => "simple",

                        "search" => false,
                        "orderable" => false,
                    ],

                    [
                        "key" => "statusdato",
                        "type" => "simple",

                        "search" => false,
                        "orderable" => false,
                    ],

                    [
                        "key" => "seriedokid",
                        "type" => "simple",
                        "search" => [
                            "operators" => [
                                Operators::EQUALS,
                            ],
                            "case" => "LOWER_CASE",
                        ],
                        "orderable" => false,
                    ],

                    [
                        "key" => "har_hefter",
                        "type" => "simple",

                        "search" => false,
                        "orderable" => false,
                    ],

                ],
            ],
        ],
    ];
}
