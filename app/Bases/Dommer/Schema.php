<?php

namespace App\Bases\Dommer;

use App\Schema\Operators;
use App\Schema\Schema as BaseSchema;

class Schema extends BaseSchema
{
    protected $schema = [
        "id" => "dommer",
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
                ],
                "edit" => [
                    "preload" => true,
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
                    "operators" => [
                        Operators::IN_RANGE,
                        Operators::OUTSIDE_RANGE,
                        Operators::IS_NULL,
                        Operators::NOT_NULL,
                    ],
                ],
            ],
            [
                "key" => "side",
                "type" => "simple",

                "search" => [
                    "operators" => [
                        Operators::EQUALS,
                        Operators::NOT_EQUALS,
                        Operators::IS_NULL,
                        Operators::NOT_NULL,
                    ],
                ],
            ],
        ],

        "groups" => [],
    ];
}
