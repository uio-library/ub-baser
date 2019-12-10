<?php

namespace App\Bases\Bibliomanuel;

use App\Schema\Schema as BaseSchema;

class Schema extends BaseSchema
{
    public $prefix = 'bibliomanuel';

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
                'searchable' => 'disabled',

                'columnClassName' => 'dt-body-nowrap',
            ],

            // Sist endret
            [
                'key' => 'updated_at',
                'type' => 'simple',
                'editable' => false,
                'searchable' => 'disabled',

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

			// Tittel
			[
				'key' => 'tittel',
				'type' => 'simple',
			],

			// Antalogi
			[
				'key' => 'antologi',
				'type' => 'simple',
			],

			// Boktittel
			[
				'key' => 'boktittel',
				'type' => 'simple',
			],

			// Redaktorer
			[
				'key' => 'redaktorer',
				'type' => 'simple',
			],

			// Utgivelsessted
			[
				'key' => 'utgivelsessted',
				'type' => 'simple',
			],

			// Avis
			[
				'key' => 'avis',
				'type' => 'simple',
			],

			// Tidsskriftstittel
			[
				'key' => 'tidsskriftstittel',
				'type' => 'simple',
			],

			// Nettsted
			[
				'key' => 'nettsted',
				'type' => 'simple',
			],


			// Utviger
			[
				'key' => 'utgiver',
				'type' => 'simple',
			],

			// Aar
			[
				'key' => 'aar',
				'type' => 'simple',
			],

			// Dato
			[
				'key' => 'dato',
				'type' => 'simple',

				'searchable' => 'advanced',
			],

			// Type
			[
				'key' => 'type',
				'type' => 'simple',
			],

			// Bind
			[
				'key' => 'bind',
				'type' => 'simple',
			],


			// Hefte
			[
				'key' => 'hefte',
				'type' => 'simple',
			],

			// Nummer
			[
				'key' => 'nummer',
				'type' => 'simple',
			],

			// Sidetall
			[
				'key' => 'sidetall',
				'type' => 'simple',
			],


			// ISBN
			[
				'key' => 'isbn',
				'type' => 'simple',
			],

			// ISSN
			[
				'key' => 'issn',
				'type' => 'simple',
			],

			// EISSN
			[
				'key' => 'eissn',
				'type' => 'simple',
			],

			// URL
			[
				'key' => 'url',
				'type' => 'simple',
			],
          ],
        ],
      ],
    ];
}
