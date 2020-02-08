<?php

namespace App\Bases\Bibliomanuel;

use App\Schema\Schema as BaseSchema;

class Schema extends BaseSchema
{
    protected $schema = [
        "id" => "bibliomanuel",
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

            // Forfatter
            [
                "key" => "forfatter",
                "type" => "autocomplete",
            ],

            // Tittel
            [
                "key" => "tittel",
                "type" => "simple",
            ],

            // Antologi
            [
                "key" => "antologi",
                "type" => "simple",
            ],

            // Boktittel
            [
                "key" => "boktittel",
                "type" => "simple",
            ],

            // Redaktører
            [
                "key" => "redaktorer",
                "type" => "simple",
            ],

            // Utgivelsessted
            [
                "key" => "utgivelsessted",
                "type" => "simple",
            ],

            // Avis
            [
                "key" => "avis",
                "type" => "simple",
            ],

            // Tidsskriftstittel
            [
                "key" => "tidsskriftstittel",
                "type" => "simple",
            ],

            // Nettsted
            [
                "key" => "nettsted",
                "type" => "simple",
            ],

            // Utviger
            [
                "key" => "utgiver",
                "type" => "simple",
            ],

            // År
            [
                "key" => "aar",
                "type" => "simple",
            ],

            // Dato
            [
                "key" => "dato",
                "type" => "simple",

                "search" => [
                    "advanced" => true,
                ],
            ],

            // Type
            [
                "key" => "type",
                "type" => "simple",
            ],

            // Bind
            [
                "key" => "bind",
                "type" => "simple",
            ],

            // Hefte
            [
                "key" => "hefte",
                "type" => "simple",
            ],

            // Nummer
            [
                "key" => "nummer",
                "type" => "simple",
            ],

            // Sidetall
            [
                "key" => "sidetall",
                "type" => "simple",
            ],

            // ISBN
            [
                "key" => "isbn",
                "type" => "simple",
            ],

            // ISSN
            [
                "key" => "issn",
                "type" => "simple",
            ],

            // EISSN
            [
                "key" => "eissn",
                "type" => "simple",
            ],

            // URL
            [
                "key" => "url",
                "type" => "simple",
            ],
        ],
    ];
}
