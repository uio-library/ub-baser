<?php

namespace App\Bases\Letras;

use App\Schema\Schema as BaseSchema;

class Schema extends BaseSchema
{
    protected $schema = [
        "id" => "letras",
        "fields" => [

            // ID
            [
                "key" => "id",
                "type" => "incrementing",
                "displayable" => true,
            ],

            // Sist endret
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
                "key" => "tekst",
                "fields" => [

                    // Forfatter
                    [
                        "key" => "forfatter",
                        "type" => "autocomplete",
                        "search" => [
                            "placeholder" => "Name of author",
                            "type" => "simple",
                        ],
                    ],

                    // Land
                    [
                        "key" => "land",
                        "type" => "autocomplete",
                        "search" => [
                            "placeholder" => "Country name",
                            "type" => "simple",
                        ],
                    ],

                    // Tittel
                    [
                        "key" => "tittel",
                        "type" => "simple",
                        "search" => [
                            "placeholder" => "Title",
                            "type" => "simple",
                        ],
                    ],

                    // Utgitt i
                    [
                        "key" => "utgitti",
                        "type" => "simple",
                        "search" => [
                            "placeholder" => "Utgitt i",
                            "type" => "simple",
                        ],
                    ],

                    // Utgivelsesår
                    [
                        "key" => "utgivelsesaar",
                        "type" => "simple",
                        "search" => [
                            "placeholder" => "Published year",
                            "type" => "simple",
                        ],
                    ],

                    // Sjanger
                    [
                        "key" => "sjanger",
                        "type" => "autocomplete",
                        "search" => [
                            "placeholder" => "Genre",
                            "type" => "simple",
                        ],
                    ],
                ],
            ],

            [
                "key" => "oversettelse",
                "fields" => [

                    // Oversetter
                    [
                        "key" => "oversetter",
                        "type" => "simple",
                        "search" => [
                            "placeholder" => "Name of translator",
                            "type" => "simple",
                        ],
                    ],

                    // Tittel
                    [
                        "key" => "tittel2",
                        "type" => "simple",
                        "search" => [
                            "placeholder" => "Title",
                            "type" => "simple",
                        ],

                    ],

                    // Utgitt i
                    [
                        "key" => "utgitti2",
                        "type" => "simple",
                        "search" => [
                            "placeholder" => "Utgitt i",
                            "type" => "simple",
                        ],
                    ],

                    // Utgivelsessted
                    [
                        "key" => "utgivelsessted",
                        "type" => "simple",
                        "search" => [
                            "placeholder" => "Place of publication",
                            "type" => "simple",
                        ],
                    ],

                    // Utgivelsesår
                    [
                        "key" => "utgivelsesaar2",
                        "type" => "simple",
                        "search" => [
                            "placeholder" => "Year of publication",
                            "type" => "simple",
                        ],
                    ],

                    // Forlag
                    [
                        "key" => "forlag",
                        "type" => "simple",
                        "search" => [
                            "placeholder" => "Publisher",
                            "type" => "simple",
                        ],
                    ],

                    // Forord/etterord
                    [
                        "key" => "foretterord",
                        "type" => "simple",
                        "search" => [
                            "placeholder" => "Foreword",
                            "advanced" => true,
                        ],
                    ],

                    // Språk
                    [
                        "key" => "spraak",
                        "type" => "simple",
                        "search" => [
                            "placeholder" => "Language",
                            "type" => "simple",
                        ],

                    ],
                ],
            ],
        ],
    ];
}
