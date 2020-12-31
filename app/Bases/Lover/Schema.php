<?php

namespace App\Bases\Lover;

use App\Schema\Operators;
use App\Schema\Schema as BaseSchema;

class Schema extends BaseSchema
{
    protected $schema = [
        "id" => "lover",
        "fields" => [
            // ID
            [
                "key" => "id",
                "type" => "incrementing",
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
    ];
}
