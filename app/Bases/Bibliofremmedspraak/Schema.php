<?php

namespace App\Bases\Bibliofremmedspraak;

use App\Schema\Schema as BaseSchema;

class Schema extends BaseSchema
{
    protected $schema = [
        'id' => 'bibliofremmedspraak',
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

            // Tidsskrift
            [
                'key' => 'tidsskrift',
                'type' => 'simple',
            ],

            // Antologi
            [
                'key' => 'antologi',
                'type' => 'simple',
            ],

            // Utgitt i
            [
                'key' => 'utgitti',
                'type' => 'simple',
            ],

            // Utgivelsessted
            [
                'key' => 'utgivelsessted',
                'type' => 'simple',
            ],

            // Nummer
            [
                'key' => 'nummer',
                'type' => 'simple',
            ],

            // Utgivelsesår
            [
                'key' => 'utgivelsesaar',
                'type' => 'simple',
            ],

            // Forlag
            [
                'key' => 'forlag',
                'type' => 'simple',
            ],

            // Emne
            [
                'key' => 'emne',
                'type' => 'simple',
            ],

            // Type
            [
                'key' => 'type',
                'type' => 'simple',
            ],

            // Dato
            [
                'key' => 'dato',
                'type' => 'simple',

                'search' => [
                    'advanced' => true,
                ],
            ],

            // Utgiver
            [
                'key' => 'utgiver',
                'type' => 'simple',
            ],

            // Ansvarsangivelse
            [
                'key' => 'ansvarsangivelse',
                'type' => 'simple',
            ],

            // e-ISSN
            [
                'key' => 'eissn',
                'type' => 'simple',
            ],

            // ISSN
            [
                'key' => 'issn',
                'type' => 'simple',
            ],

            // ISBN
            [
                'key' => 'isbn',
                'type' => 'simple',
            ],

            // EISBN
            [
                'key' => 'eisbn',
                'type' => 'simple',
            ],

            // Boktittel
            [
                'key' => 'boktittel',
                'type' => 'simple',
            ],
            // Nettsted
            [
                'key' => 'nettsted',
                'type' => 'simple',
            ],
            // Språk
            [
                'key' => 'spraak',
                'type' => 'simple',
            ],
            // URL
            [
                'key' => 'url',
                'type' => 'simple',
            ],

            // Sidetall
            [
                'key' => 'sidetall',
                'type' => 'simple',
            ],

            // Digitalisering
            [
                'key' => 'digitalisering',
                'type' => 'simple',
            ],
        ],
    ];
}
