<?php

namespace App\Bases\Lover;

use App\Schema\Schema as BaseSchema;

class LovSchema extends BaseSchema
{

    // 'key': Oversettelsesnøkkel, se resources/lang/nb/oversatte_lover.php

    protected $schema = [
        'id' => 'oversatte_lover.lov',
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

            // Lov - Tittel
            [
                'key' => 'tittel',
                'type' => 'autocomplete',
            ],

            // Lov - Kort tittel
            [
                'key' => 'kort_tittel',
                'type' => 'autocomplete',
            ],

            // Dokumenttype
            [
                'key' => 'dok_type',
                'type' => 'select',
                'values' => [
                    ['value' => 'lov', 'prefLabel' => 'lov'],
                    ['value' => 'forskrift', 'prefLabel' => 'forskrift'],
                ],
            ],

            // Oversettelser
            [
                'key' => 'oversettelser',
                'type' => 'entities',
                'entityType' => Oversettelse::class,
                'entitySchema' => Schema::class,
                'modelAttribute' => 'oversettelser',
                'pivotFields' => [],
            ],

            // Språk
            [
                'key' => 'oversettelse_sprak',
                'type' => 'select',
                'edit' => [
                    'preload' => true, // Se AutocompleteService
                ],
            ],

            // Visningsfelt: Oversettelse - Tittel
            [
                'key' => 'oversettelse_tittel',
                'type' => 'autocomplete',
                'edit' => false, // fordi det redigeres gjennom OversettelseSchema
            ],

            // Visningsfelt: Oversettelse - Kort tittel
            [
                'key' => 'oversettelse_kort_tittel',
                'type' => 'autocomplete',
                'edit' => false, // fordi det redigeres gjennom OversettelseSchema
            ],
        ],
    ];
}
