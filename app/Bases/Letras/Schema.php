<?php

namespace App\Bases\Letras;

use App\Schema\Schema as BaseSchema;

class Schema extends BaseSchema
{
    public $prefix = 'letras';

    protected $schema = [
        'fields' => [

            // ID
            [
                'key' => 'id',
                'type' => 'incrementing',
                'displayable' => true,
            ],

            // Sist endret
            [
                'key' => 'created_at',
                'type' => 'simple',
                'editable' => false,
                'search' => false,

                'columnClassName' => 'dt-body-nowrap',
            ],

            // Sist endret
            [
                'key' => 'updated_at',
                'type' => 'simple',
                'editable' => false,
                'search' => false,

                'columnClassName' => 'dt-body-nowrap',
            ],
        ],

        'groups' => [

            [
                'label' => 'Verket',
                'fields' => [

                    // Forfatter
                    [
                        'key' => 'forfatter',
                        'type' => 'autocomplete',
                    ],

                    // Land
                    [
                        'key' => 'land',
                        'type' => 'autocomplete',
                    ],

                    // Tittel
                    [
                        'key' => 'tittel',
                        'type' => 'simple',
                    ],

                    // Utgivelsesår
                    [
                        'key' => 'utgivelsesaar',
                        'type' => 'simple',
                    ],

                    // Sjanger
                    [
                        'key' => 'sjanger',
                        'type' => 'autocomplete',
                    ],
                ],
            ],

            [
                'label' => 'Oversettelsen',
                'fields' => [

                    // Oversetter
                    [
                        'key' => 'oversetter',
                        'type' => 'simple',
                    ],

                    // Tittel
                    [
                        'key' => 'tittel2',
                        'type' => 'simple',
                    ],

                    // Utgivelsessted
                    [
                        'key' => 'utgivelsessted',
                        'type' => 'simple',
                    ],

                    // Utgivelsesår
                    [
                        'key' => 'utgivelsesaar2',
                        'type' => 'simple',
                    ],

                    // Forlag
                    [
                        'key' => 'forlag',
                        'type' => 'simple',
                    ],

                    // Forord/etterord
                    [
                        'key' => 'foretterord',
                        'type' => 'simple',

                        'search' => [
                            'advanced' => true,
                        ],
                    ],

                    // Språk
                    [
                        'key' => 'spraak',
                        'type' => 'simple',
                    ],
                ],
            ],
        ],
    ];
}
