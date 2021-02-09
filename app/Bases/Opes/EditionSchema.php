<?php

namespace App\Bases\Opes;

use App\Schema\Schema as BaseSchema;

class EditionSchema extends BaseSchema
{
    protected $schema = [
        'id' => 'opes.edition',
        'fields' => [

            // Internal sort field
            [
                'key' => 'edition_nr',
                'type' => 'simple',
                'edit' => [
                    'placeholder' => '',
                    'help' => 'Internal sort field.',
                ],

            ],

            // Citation components: Editor
            [
                'key' => 'editor',
                'type' => 'simple',
            ],

            // Citation components: Series + volume
            [
                'key' => 'ser_vol',
                'type' => 'simple',
            ],

            // Citation components: Year
            [
                'key' => 'year',
                'type' => 'simple',
                'search' => false,
            ],

            // Citation components: Page number
            [
                'key' => 'pg_no',
                'type' => 'simple',
                'search' => false,
            ],

            // Citation components: Page number for photo
            [
                'key' => 'photo',
                'type' => 'simple',
                'search' => false,
            ],

            // Corrections
            [
                'key' => 'corrections',
                'type' => 'simple',
                'multiline' => true,

                'edit' => [
                    'placeholder' => '',
                    'help' => 'Multiple corrections should be separated by semicolon followed by space.',
                ],
            ],

            // SB
            [
                'key' => 'sb',
                'type' => 'simple',
                'search' => false,
            ],

            // Everything in one field. Deprecated
            [
                'key' => 'preferred_citation',
                'type' => 'simple',
                'multiline' => true,
            ],
        ],
    ];
}
