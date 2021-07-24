<?php

namespace App\Bases\Nordskrift;

use App\Schema\Schema as BaseSchema;

class Schema extends BaseSchema
{
    protected $schema = [
        'id' => 'nordskrift',
        'fields' => [

            // ID
            [
                'key' => 'id',
                'type' => 'incrementing',
                'showInRecordView' => true,
            ],

            // Sist endret
            [
                'key' => 'created_at',
                'type' => 'simple',
                'edit' => false,
                'search' => false,
                'columnClassName' => 'dt-body-nowrap',
            ],

            // Sist endret
            [
                'key' => 'updated_at',
                'type' => 'simple',
                'edit' => false,
                'search' => false,
                'columnClassName' => 'dt-body-nowrap',
            ],
            // Forfatter
            [
                'key' => 'forfatter',
                'type' => 'autocomplete',
            ],

            // Tittel
            [
                'key' => 'tittel',
                'type' => 'simple',
            ],

            // Sekundærtittel
            [
                'key' => 'sekundartittel',
                'type' => 'simple',
            ],

            // Sider
            [
                'key' => 'sider',
                'type' => 'simple',
            ],

            // Nøkkerord
            [
                'key' => 'nokkelord',
                'type' => 'simple',
            ],

            // Dato
            [
                'key' => 'dato',
                'type' => 'simple',
            ],

            // Utgivelsessted
            [
                'key' => 'utgivelsessted',
                'type' => 'simple',
            ],

            // Utgiver
            [
                'key' => 'utgiver',
                'type' => 'simple',
            ],

            // ISBN
            [
                'key' => 'isbn',
                'type' => 'simple',
            ],

            // Abstrakt
            [
                'key' => 'abstrakt',
                'type' => 'simple',
            ],

            // Notater
            [
                'key' => 'notater',
                'type' => 'simple',
            ],

            // elektroniskressursnummer
            [
                'key' => 'elektroniskressursnummer',
                'type' => 'simple',
            ],
        ],
    ];
}
