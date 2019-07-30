<?php

namespace App\Letras;

use App\BaseSchema;

class LetrasSchema extends BaseSchema
{
    public $prefix = 'letras';

    protected $schema = [
        'fields' => [

            // ID
            [
                'key' => 'id',
                'type' => 'incrementing',
                'display' => false,
                'edit' => false,
                'search' => false,
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
                        'search' => [
                            'advanced' => true,
                        ],
                    ],

                    // Forlag
                    [
                        'key' => 'forlag',
                        'type' => 'simple',
                        'search' => [
                        ],
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

    public function __construct()
    {
        $this->init([
            'minYear' => 1789,
            'maxYear' => (int)strftime('%Y'),
            'autocompleTarget' => action('LetrasController@autocomplete'),
        ]);
    }
}