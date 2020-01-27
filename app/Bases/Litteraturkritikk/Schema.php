<?php

namespace App\Bases\Litteraturkritikk;

use App\Schema\Schema as BaseSchema;

class Schema extends BaseSchema
{
    public $prefix = 'litteraturkritikk';
    protected $schema = [
        'fields' => [

            // ID
            [
                'key' => 'id',
                'type' => 'incrementing',
            ],

            // Søk i alle felt
            [
                'key' => 'q',
                'type' => 'simple',

                'displayable' => false,
                'editable' => false,
                'searchable' => 'simple',

                'searchOptions' => [
                    'placeholder' => 'Forfatter, kritiker, ord i tittel, kommentar, etc... Avslutt med * om du føler for å trunkere.',
                    'index' => ['type' => 'ts', 'ts_column' => 'any_field_ts'],
                    'operators' => ['eq', 'neq'],
                ],
            ],

            // Person-søk (forfatter eller kritiker)
            [
                'key' => 'person',
                'type' => 'autocomplete',

                'displayable' => false,
                'editable' => false,
                'searchable' => 'simple',

                'searchOptions' => [
                    'placeholder' => 'Fornavn og/eller etternavn',
                    'index' => ['type' => 'ts', 'ts_column' => 'person_ts'],
                    'operators' => ['eq', 'neq'],
                ],
            ],
        ],

        'groups' => [

            // Verket
            [
                'label' => 'Omtale av',
                'fields' => [

                    // Tittel
                    [
                        'key' => 'verk_tittel',
                        'type' => 'autocomplete',

                        'searchOptions' => [
                            'placeholder' => 'Tittel på omtalt verk',
                            'index' => [
                                'type' => 'ts',
                                'column' => 'verk_tittel',
                                'ts_column' => 'verk_tittel_ts',
                            ],
                        ],
                    ],

                    // Originaltittel
                    [
                        'key' => 'verk_originaltittel',
                        'type' => 'autocomplete',
                        'searchable' => 'disabled',
                    ],

                    // Forfatter
                    [
                        'key' => 'verk_forfatter',
                        'type' => 'persons',

                        'modelAttribute' => 'forfattere',
                        'personRole' => 'forfatter',

                        'searchOptions' => [
                            'type' => 'autocomplete',
                            'placeholder' => 'Fornavn og/eller etternavn',
                            'index' => [
                                'type' => 'ts',
                                'column' => 'verk_forfatter',
                                'ts_column' => 'forfatter_ts',
                            ],
                            'operators' => [
                                'eq',
                                'neq',
                                'isnull',
                                'notnull',
                            ],
                        ],
                    ],

                    // mfl.
                    [
                        'key' => 'verk_forfatter_mfl',
                        'type' => 'boolean',

                        'displayable' => false,
                        'searchable' => 'disabled',

                        // 'default' => false,
                        'help' => 'Kryss av hvis det er flere personer enn dem som er listet opp eksplisitt ovenfor.',
                    ],

                    // År
                    [
                        'key' => 'verk_dato',
                        'type' => 'simple',

                        'columnClassName' => 'dt-body-nowrap',

                        'searchOptions' => [
                            'type' => 'rangeslider',
                            'advanced' => true,
                            'index' => [
                                'type' => 'range',
                                'column' => 'verk_dato',
                            ],
                        ],
                    ],

                    // Sjanger
                    [
                        'key' => 'verk_sjanger',
                        'type' => 'autocomplete',

                        'searchOptions' => [
                            'placeholder' => 'Sjanger til det omtalte verket. F.eks. lyrikk, roman, ...',
                            'options' => [],
                            'index' => ['type' => 'simple', 'column' => 'verk_sjanger'],
                        ],
                    ],

                    // Språk
                    [
                        'key' => 'verk_spraak',
                        'type' => 'autocomplete',

                        'searchable' => 'advanced',
                        'searchOptions' => [
                            'placeholder' => 'Språk for den omtalte utgaven',
                        ],
                    ],

                    // Originalspråk
                    [
                        'key' => 'verk_originalspraak',
                        'type' => 'autocomplete',

                        'searchable' => 'advanced',
                        'searchOptions' => [
                            'placeholder' => 'Språk for originalutgaven',
                        ],
                    ],

                    // Kommentar
                    [
                        'key' => 'verk_kommentar',
                        'type' => 'simple',

                        'searchable' => 'advanced',
                    ],

                    // Utgivelsessted
                    [
                        'key' => 'verk_utgivelsessted',
                        'type' => 'autocomplete',

                        'searchable' => 'advanced',
                    ],

                    // Fulltekst-URL
                    [
                        'key' => 'verk_fulltekst_url',
                        'type' => 'url',
                        'help' => 'Flere verdier skilles med mellomrom',

                        'searchable' => 'advanced',
                    ],

                ],
            ],

            // Kritikken
            [
                'label' => 'Kritikken',
                'fields' => [

                    // Kritiker
                    [
                        'key' => 'kritiker',
                        'type' => 'persons',

                        'modelAttribute' => 'kritikere',
                        'personRole' => 'kritiker',

                        'searchOptions' => [
                            'type' => 'autocomplete',
                            'placeholder' => 'Fornavn og/eller etternavn',
                            'index' => [
                                'type' => 'ts',
                                'column' => 'kritiker',
                                'ts_column' => 'kritiker_ts',
                            ],
                        ],
                    ],

                    // mfl.
                    [
                        'key' => 'kritiker_mfl',
                        'type' => 'boolean',
                        'help' => 'Kryss av hvis det er flere personer enn dem som er listet opp eksplisitt ovenfor.',
                        // 'default' => false,
                        'displayable' => false,
                        'searchable' => 'disabled',
                    ],

                    // Publikasjon
                    [
                        'key' => 'publikasjon',
                        'type' => 'autocomplete',

                        'searchOptions' => [
                            'placeholder' => 'Publikasjon',
                            'index' => [
                                'type' => 'simple',
                                'column' => 'publikasjon',
                            ],
                        ],
                    ],

                    // Medieformat
                    [
                        'key' => 'medieformat',
                        'type' => 'enum',
                        'columnClassName' => 'dt-body-nowrap',
                        'values' => [
                            ['id' => 'avis', 'label' => 'Avis'],
                            ['id' => 'tidsskrift', 'label' => 'Tidsskrift'],
                            ['id' => 'bok', 'label' => 'Bok'],
                            ['id' => 'radio', 'label' => 'Radio'],
                            ['id' => 'tv', 'label' => 'TV'],
                            ['id' => 'video', 'label' => 'Video'],
                            ['id' => 'blogg', 'label' => 'Blogg'],
                            ['id' => 'podcast', 'label' => 'Podcast'],
                            ['id' => 'nettmagasin', 'label' => 'Nettmagasin'],
                            ['id' => 'nettforum', 'label' => 'Nettforum'],
                            ['id' => 'some', 'label' => 'Sosiale medier'],
                        ],
                        'searchOptions' => [
                            'operators' => ['ex'],
                        ],
                    ],

                    // Type
                    [
                        'key' => 'kritikktype',
                        'type' => 'tags',
                        'defaultValue' => [],

                        'searchOptions' => [
                            'type' => 'autocomplete',
                            'placeholder' => '',
                            'index' => [
                                'type' => 'array',
                                'column' => 'kritikktype',
                            ],
                        ],
                    ],

                    // Dato
                    [
                        'key' => 'dato',
                        'type' => 'simple',

                        'columnClassName' => 'dt-body-nowrap',

                        'searchOptions' => [
                            'type' => 'rangeslider',
                            'index' => [
                                'type' => 'range',
                                'column' => 'dato',
                            ],
                        ],
                    ],

                    // Språk
                    [
                        'key' => 'spraak',
                        'type' => 'autocomplete',

                        'searchable' => 'advanced',
                    ],

                    // Tittel
                    [
                        'key' => 'tittel',
                        'type' => 'simple',

                        'searchable' => 'advanced',
                    ],

                    [
                        'key' => 'utgivelsessted',
                        'type' => 'autocomplete',

                        'searchable' => 'advanced',
                    ],
                    [
                        'key' => 'aargang',
                        'type' => 'simple',
                        'help' => 'Oppgis som tall (1), intervall (1-2) eller flere verdier adskilt av semikolon fulgt av mellomrom (1; 3).',
                    ],
                    [
                        'key' => 'bind',
                        'type' => 'simple',
                        'help' => 'Oppgis som tall (1), intervall (1-2) eller flere verdier adskilt av semikolon fulgt av mellomrom (1; 3).',
                    ],
                    [
                        'key' => 'hefte',
                        'type' => 'simple',
                        'help' => 'Oppgis som tall (1), intervall (1-2) eller flere verdier adskilt av semikolon fulgt av mellomrom (1; 3).',
                    ],
                    [
                        'key' => 'nummer',
                        'type' => 'simple',
                        'help' => 'Oppgis som tall (1), intervall (1-2) eller flere verdier adskilt av semikolon fulgt av mellomrom (1; 3).',
                    ],
                    [
                        'key' => 'sidetall',
                        'type' => 'simple',
                        'help' => 'Oppgis som tall (1), intervall (1-2) eller flere verdier adskilt av semikolon fulgt av mellomrom (1; 3).',
                    ],
                    [
                        'key' => 'kommentar',
                        'type' => 'simple',

                        'searchable' => 'advanced',
                    ],
                    [
                        'key' => 'utgivelseskommentar',
                        'type' => 'simple',

                        'searchable' => 'advanced',
                    ],
                    [
                        'key' => 'fulltekst_url',
                        'type' => 'url',
                        'help' => 'Flere verdier skilles med mellomrom',

                        'searchable' => 'advanced',
                    ],

                    // Emneord
                    [
                        'key' => 'tags',
                        'type' => 'tags',
                        'defaultValue' => [],

                        'searchOptions' => [
                            'type' => 'autocomplete',
                            'placeholder' => 'F.eks. teaterkritikk, forfatterportrett, ...',
                            'index' => [
                                'type' => 'array',
                                'column' => 'tags',
                            ],
                        ],
                    ],
                ],
            ],

            // Posten
            [
                'label' => 'Databaseposten',

                'fields' => [

                    // Opprettet
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

                    // Korrekturstatus
                    [
                        'key' => 'korrekturstatus',
                        'type' => 'enum',
                        'datatype' => self::DATATYPE_INT,
                        'values' => [
                            ['id' => 1, 'label' => 'Ikke korrekturlest'],
                            ['id' => 2, 'label' => 'Må korrekturleses mot fysisk materiale'],
                            ['id' => 3, 'label' => 'Korrekturlest mot fysisk materiale'],
                            ['id' => 4, 'label' => 'Korrekturlest mot og lenket til digitalt materiale'],
                        ],
                        'searchOptions' => [
                            'operators' => ['ex'],
                        ],
                        'columnClassName' => 'dt-body-nowrap',
                    ],

                    // Slettet
                    [
                        'key' => 'deleted_at',
                        'type' => 'simple',
                        'editable' => false,
                        'searchable' => 'disabled',

                        'columnClassName' => 'dt-body-nowrap',
                    ],
                ],
            ],
        ],
    ];

    public function __construct()
    {
        $this->schemaOptions['minYear'] = 1789;
        $this->schemaOptions['maxYear'] = (int) strftime('%Y');

        parent::__construct();
    }
}
