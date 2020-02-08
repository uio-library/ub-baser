<?php

namespace App\Bases\Dommer;

use App\Schema\Schema as BaseSchema;

class Schema extends BaseSchema
{
    public $prefix = "dommer";
    protected $schema = [
        "fields" => [

            // ID
            [
                "key" => "id",
                "type" => "incrementing",
            ],

            [
                "key" => "navn",
                "type" => "simple",
            ],
            [
                "key" => "kilde",
                "type" => "select",

                "column" => "kilde_id",
                "viewColumn" => "kilde_navn",

                "search" => [
                    "type"=> "simple",
                    "index" => "kilde_id",
                    "operators" => ["ex"],
                ],
            ],
            [
                "key" => "aar",
                "type" => "simple",

                "columnClassName" => "dt-body-nowrap",

                "search" => [
                    "type" => "range",
                    "widget" => "rangeslider",
                    "widgetOptions" => [
                        "minValue" => 1848,
                        "maxValue" => 2012,
                    ],
                ],
            ],
            [
                "key" => "side",
                "type" => "simple",
            ],
        ],

        "groups" => [],
    ];
}
