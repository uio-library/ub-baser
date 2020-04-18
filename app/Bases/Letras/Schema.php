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
                "showInRecordView" => true,
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
                    ],

                    // Land
                    [
                        "key" => "land",
                        "type" => "autocomplete",
                    ],

                    // Tittel
                    [
                        "key" => "tittel",
                        "type" => "simple",
                    ],

                    // Utgitt i
                    [
                        "key" => "utgitti",
                        "type" => "autocomplete",
                    ],

                    // Utgivelsesår
                    [
                        "key" => "utgivelsesaar",
                        "type" => "simple",
                    ],

                    // Sjanger
                    [
                        "key" => "sjanger",
                        "type" => "autocomplete",
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
                        "type" => "autocomplete",
                    ],

                    // Utgivelsessted
                    [
                        "key" => "utgivelsessted",
                        "type" => "autocomplete",
                    ],

                    // Utgivelsesår
                    [
                        "key" => "utgivelsesaar2",
                        "type" => "simple",
                    ],

                    // Forlag
                    [
                        "key" => "forlag",
                        "type" => "autocomplete",
                    ],

                    // Forord/etterord
                    [
                        "key" => "foretterord",
                        "type" => "simple",
                        "search" => [
                            "advanced" => true,
                        ],
                    ],

                    // Språk
                    [
                        "key" => "spraak",
                        "type" => "autocomplete",
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
