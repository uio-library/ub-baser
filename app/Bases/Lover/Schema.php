<?php

namespace App\Bases\Lover;

use App\Schema\Operators;
use App\Schema\Schema as BaseSchema;

class Schema extends BaseSchema
{
    protected $schema = [
        'id' => 'oversatte_lover',
        'fields' => [

            // ID
            [
                'key' => 'id',
                'type' => 'incrementing',
            ],
            // Opprettet
            [
                'key' => 'created_at',
                'type' => 'simple',
                'edit' => false,
                'search' => false, // Ikke vis i søkeskjemaet
                'columnClassName' => 'dt-body-nowrap',
            ],
            // Sist endret
            [
                'key' => 'updated_at',
                'type' => 'simple',
                'edit' => false,
                'search' => false, // Ikke vis i søkeskjemaet
                'columnClassName' => 'dt-body-nowrap',
            ],

            // Oversettelse - Tittel
            [
                'key' => 'tittel',
                'type' => 'simple',
                'edit' => [
                    'placeholder' => '',
                    'help' => 'Hjelpetekst',
                ],
            ],

            // Oversettelse - Kort tittel
            [
                'key' => 'kort_tittel',
                'type' => 'simple',
                'edit' => [
                    'placeholder' => '',
                    'help' => 'Hjelpetekst',
                ],
            ],

            // Oversettelse - Språk
            [
                'key' => 'sprak',
                'type' => 'select',
                'values' => [
                    ['value' => 'ENG', 'prefLabel' => 'engelsk'],
                    ['value' => 'SPA', 'prefLabel' => 'spansk'],
                    ['value' => 'FRE', 'prefLabel' => 'fransk'],
                    ['value' => 'GER', 'prefLabel' => 'tysk'],
                    // osv...
                ],

                'edit' => [
                    'allow_new_values' => false,
                    'placeholder' => '',
                    'help' => 'Hjelpetekst',
                ],
            ],

            // Lov - Tittel
            [
                'key' => 'lov_tittel',
                'type' => 'simple',
                'edit' => [
                    'placeholder' => '',
                    'help' => 'Hjelpetekst',
                ],
            ],

            // Lov - Dato
            [
                'key' => 'lov_dato',
                'type' => 'date',
                'edit' => [
                    'placeholder' => '',
                    'help' => 'Hjelpetekst',
                ],
                'search' => [
                    'type' => 'range',
                    'widget' => 'rangeslider',
                    'widgetOptions' => [
                        'minValue' => 1741,
                    ],
                ],
            ],

            // Lov - Nummer
            [
                'key' => 'lov_nummer',
                'type' => 'simple',
                'edit' => [
                    'placeholder' => '',
                    'help' => 'Hjelpetekst',
                ],
                'search' => [
                    'advanced' => false, // true for å bare vise feltet i avansert søk
                ]
            ],

            // Lov - Dokumenttype
            [
                'key' => 'lov_dok_type',
                'type' => 'select',
                'values' => [
                    ['value' => 'lov', 'prefLabel' => 'lov'],
                    ['value' => 'forskrift', 'prefLabel' => 'forskrift'],
                ],
            ],

        ],
    ];
}
