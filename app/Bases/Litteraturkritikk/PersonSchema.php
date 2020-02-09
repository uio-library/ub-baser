<?php

namespace App\Bases\Litteraturkritikk;

use App\Schema\Schema as BaseSchema;

class PersonSchema extends BaseSchema
{
    protected $schema = [
        "id" => "litteraturkritikk.person",
        "fields" => [
            [
                "key" => "etternavn",
                "type" => "simple",
                "edit" => [
                    "placeholder" => "",
                    "help" => "YO",
                ]
            ],
            [
                "key" => "fornavn",
                "type" => "simple",
                "edit" => [
                    "placeholder" => "",
                    "help" => "Fyll inn fornavn og eventuelle mellomnavn.",
                ]
            ],
            [
                "key" => "kjonn",
                "type" => "enum",
                "values" => [
                    ["id" => "u", "label" => "Ukjent"],
                    ["id" => "m", "label" => "Mann"],
                    ["id" => "f", "label" => "Kvinne"],
                ],
                "edit" => [
                    "help" => "YO",
                ]
            ],
            [
                "key" => "fodt",
                "type" => "simple",
                "edit" => [
                    "help" => "YO",
                ]
            ],
            [
                "key" => "dod",
                "type" => "simple",
                "edit" => [
                    "help" => "YO",
                ]
            ],
        ],
    ];
}
