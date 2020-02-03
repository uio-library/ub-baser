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

                'search' => [
                    'placeholder' => 'Forfatter, kritiker, ord i tittel, kommentar, etc... Avslutt med * om du føler for å trunkere.',
                    'type' => 'ts',
                    'index' => 'any_field_ts',
                    'operators' => ['eq', 'neq'],
                ],
            ],

            // Person-søk (forfatter eller kritiker)
            [
                'key' => 'person',
                'type' => 'autocomplete',

                'displayable' => false,
                'editable' => false,

                'search' => [
                    'placeholder' => 'Fornavn og/eller etternavn',
                    'type' => 'ts',
                    'index' => 'person_ts',
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

                        'search' => [
                            'placeholder' => 'Tittel på omtalt verk',
                            'type' => 'ts',
                            'index' => 'verk_tittel_ts',
                        ],

                        // 'edit' => [
                        //     'placeholder' => 'Tittel på den omtalte utgaven av verket',
                        // ],
                    ],

                    // Språk
                    [
                        'key' => 'verk_spraak',
                        'type' => 'autocomplete',

                        'search' => [
                            'advanced' => true,
                            'placeholder' => 'Språket den omtalte utgaven er skrevet på',
                        ],
                    ],

                    // Originaltittel
                    [
                        'key' => 'verk_originaltittel',
                        'type' => 'autocomplete',

                        'search' => [
                            'advanced' => true,
                            'placeholder' => 'Søk kun i originaltitler',
                        ],
                        // 'edit' => [
                        //     'placeholder' => 'Fylles ut hvis tittel på omtalt utgave avviker fra originaltittel, f.eks. ved oversettelse',
                        // ],

                    ],

                    // Originaltittel (transkribert)
                    [
                        'key' => 'verk_originaltittel_transkribert',
                        'type' => 'autocomplete',
                        'search' => [
                            'advanced' => true,
                            'placeholder' => 'Søk kun i transkriberte titler',
                        ],
                        // 'edit' => [
                        //     'placeholder' => 'Fylles ut hvis originaltittel bruker ikke-latinsk skrift',
                        // ],
                    ],

                    // Originalspråk
                    [
                        'key' => 'verk_originalspraak',
                        'type' => 'autocomplete',

                        'search' => [
                            'advanced' => true,
                            'placeholder' => 'Språket originalutgaven er skrevet på',
                        ],
                    ],

                    // Forfatter
                    [
                        'key' => 'verk_forfatter',
                        'type' => 'persons',

                        'modelAttribute' => 'forfattere',
                        'personRole' => 'forfatter',

                        'search' => [
                            'widget' => 'autocomplete',
                            'placeholder' => 'Fornavn og/eller etternavn',
                            'type' => 'ts',
                            'index' => 'forfatter_ts',
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
                        'search' => false,

                        // 'default' => false,
                        'help' => 'Kryss av hvis det er flere personer enn dem som er listet opp eksplisitt ovenfor.',
                    ],

                    // Utgivelsessted
                    [
                        'key' => 'verk_utgivelsessted',
                        'type' => 'autocomplete',

                        'search' => [
                            'advanced' => true,
                        ],
                    ],

                    // År
                    [
                        'key' => 'verk_dato',
                        'type' => 'simple',

                        'columnClassName' => 'dt-body-nowrap',

                        'search' => [
                            'advanced' => true,
                            'type' => 'range',
                            'widget' => 'rangeslider',
                            'widgetOptions' => [
                                'minValue' => 1789,
                            ],
                            'placeholder' => 'Utgivelsesår for omtalt utgave'
                        ],
                    ],

                    // Sjanger
                    [
                        'key' => 'verk_sjanger',
                        'type' => 'autocomplete',

                        'search' => [
                            'placeholder' => 'Sjanger til det omtalte verket. F.eks. lyrikk, roman, ...',
                            'type' => 'simple',
                        ],
                    ],

                    // Kommentar
                    [
                        'key' => 'verk_kommentar',
                        'type' => 'simple',

                        'search' => [
                            'advanced' => true,
                        ],
                    ],

                    // Fulltekst-URL
                    [
                        'key' => 'verk_fulltekst_url',
                        'type' => 'url',
                        'help' => 'Flere verdier skilles med mellomrom',

                        'search' => [
                            'advanced' => true,
                        ],
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

                        'search' => [
                            'widget' => 'autocomplete',
                            'placeholder' => 'Fornavn og/eller etternavn',
                            'type' => 'ts',
                            'index' => 'kritiker_ts',
                        ],
                    ],

                    // mfl.
                    [
                        'key' => 'kritiker_mfl',
                        'type' => 'boolean',
                        'help' => 'Kryss av hvis det er flere personer enn dem som er listet opp eksplisitt ovenfor.',
                        // 'default' => false,
                        'displayable' => false,
                        'search' => false,
                    ],

                    // Publikasjon
                    [
                        'key' => 'publikasjon',
                        'type' => 'autocomplete',

                        'search' => [
                            'placeholder' => 'Publikasjon',
                            'type' => 'simple',
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
                        'search' => [
                            'operators' => ['ex'],
                        ],
                    ],

                    // Type
                    [
                        'key' => 'kritikktype',
                        'type' => 'tags',
                        'defaultValue' => [],

                        'search' => [
                            'type' => 'array',
                            'widget' => 'autocomplete',
                            'placeholder' => '',
                        ],
                    ],

                    // Emneord
                    [
                        'key' => 'tags',
                        'type' => 'tags',
                        'defaultValue' => [],

                        'search' => [
                            'type' => 'array',
                            'widget' => 'autocomplete',
                            'placeholder' => 'F.eks. teaterkritikk, forfatterportrett, ...',
                        ],
                    ],

                    // Dato
                    [
                        'key' => 'dato',
                        'type' => 'simple',

                        'columnClassName' => 'dt-body-nowrap',

                        'search' => [
                            'type' => 'range',
                            'widget' => 'rangeslider',
                            'widgetOptions' => [
                                'minValue' => 1789,
                            ],
                        ],
                    ],

                    // Språk
                    [
                        'key' => 'spraak',
                        'type' => 'autocomplete',

                        'search' => [
                            'advanced' => true,
                        ],
                    ],

                    // Tittel
                    [
                        'key' => 'tittel',
                        'type' => 'simple',

                        'search' => [
                            'advanced' => true,
                        ],
                    ],

                    [
                        'key' => 'utgivelsessted',
                        'type' => 'autocomplete',

                        'search' => [
                            'advanced' => true,
                        ],
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

                        'search' => [
                            'advanced' => true,
                        ],
                    ],
                    [
                        'key' => 'utgivelseskommentar',
                        'type' => 'simple',

                        'search' => [
                            'advanced' => true,
                        ],
                    ],
                    [
                        'key' => 'fulltekst_url',
                        'type' => 'url',
                        'help' => 'Flere verdier skilles med mellomrom',

                        'search' => [
                            'advanced' => true,
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
                        'search' => [
                            'operators' => ['ex'],
                        ],
                        'columnClassName' => 'dt-body-nowrap',
                    ],

                    // Slettet
                    [
                        'key' => 'deleted_at',
                        'type' => 'simple',
                        'editable' => false,
                        'search' => false,

                        'columnClassName' => 'dt-body-nowrap',
                    ],
                ],
            ],
        ],
    ];
}
