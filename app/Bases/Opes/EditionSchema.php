<?php

namespace App\Bases\Opes;

use App\Schema\Schema as BaseSchema;

class EditionSchema extends BaseSchema
{
    protected $schema = [
        'id' => 'opes.edition',
        'fields' => [

            // Citation components: Editor
            [
                "key" => "editor",
                "type" => "simple",
            ],

            // Citation components: Series + volume
            [
                "key" => "ser_vol",
                "type" => "simple",
            ],

            // Citation components: Year
            [
                "key" => "year",
                "type" => "simple",
                'search' => false,
            ],

            // Citation components: Page number
            [
                "key" => "pg_no",
                "type" => "simple",
                'search' => false,
            ],

            // Citation components: Page number for photo
            [
                "key" => "photo",
                "type" => "simple",
                'search' => false,
            ],

            // Corrections
            [
                "key" => "corrections",
                "type" => "simple",
            ],

            // Everything in one field. Deprecated
            [
                "key" => "preferred_citation",
                "type" => "simple",
            ],
        ],
    ];
}
