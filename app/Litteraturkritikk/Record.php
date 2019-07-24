<?php

namespace App\Litteraturkritikk;

use Illuminate\Database\Eloquent\SoftDeletes;

class Record extends \App\Record
{
    use SoftDeletes;

    public static function getColumns(): array {
        $minYear = 1789;
        $maxYear = (int) strftime('%Y');

        $grouped = [
            'fields' => [

                // ID
                [
                    'key' => 'id',
                    'type' => 'incrementing',
                    'display' => false,
                    'edit' => false,
                    'search' => false,
                ],

                // Søk i alle felt
                [
                    'key' => 'q',
                    'type' => 'simple',
                    'display' => false,
                    'edit' => false,
                    'search' => [
                        'advanced' => false,
                        'placeholder' => 'Forfatter, kritiker, ord i tittel, kommentar, etc... Avslutt med * om du føler for å trunkere.',
                        'index' => ['type' => 'ts', 'ts_column' => 'any_field_ts'],
                        'operators' => ['eq', 'neq']
                    ]
                ],

                // Person-søk (forfatter eller kritiker)
                [
                    'key' => 'person',
                    'type' => 'autocomplete',
                    'display' => false,
                    'edit' => false,
                    'search' => [
                        'advanced' => false,
                        'placeholder' => 'Fornavn og/eller etternavn',
                        'index' => ['type' => 'ts', 'ts_column' => 'person_ts'],
                        'operators' => ['eq', 'neq'],
                    ]
                ],
            ],

            'groups' => [

                // Verket
                [
                    'label' => 'Verket',
                    'fields' => [

                        // Tittel
                        [
                            'key' => 'verk_tittel',
                            'type' => 'autocomplete',
                            'search' => [
                                'placeholder' => 'Tittel på omtalt verk',
                                'options' => [],
                                'index' => [
                                    'type' => 'ts',
                                    'column' => 'verk_tittel',
                                    'ts_column' => 'verk_tittel_ts',
                                ],
                            ],
                        ],

                        // Forfatter
                        [
                            'key' => 'verk_forfatter',
                            'type' => 'persons',
                            'model_attribute' => 'forfattere',
                            'person_role' => 'forfatter',
                            'search' => [
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
                            'help' => 'Kryss av hvis det er flere personer som ikke er listet opp',
                            'default' => false,
                            'display' => false,
                            'search' => false,
                        ],

                        // År
                        [
                            'key' => 'verk_dato',
                            'type' => 'simple',
                            'display' => [
                                'columnClassName' => 'dt-body-nowrap',
                            ],
                            'search' => [
                                'type' => 'rangeslider',
                                'advanced' => true,
                                'options' => [
                                    'minValue' => (int) $minYear,
                                    'maxValue' => (int) $maxYear,
                                ],
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
                            'search' => [
                                'placeholder' => 'Sjanger til det omtalte verket. F.eks. lyrikk, roman, ...',
                                'options' => [],
                                'index' => ['type' => 'simple', 'column' => 'verk_sjanger'],
                            ],
                        ],

                        // Språk
                        [
                            'key' => 'verk_spraak',
                            'type' => 'autocomplete',
                            'search' => [
                                'advanced' => true,
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

                        // Utgivelsessted
                        [
                            'key' => 'verk_utgivelsessted',
                            'type' => 'autocomplete',
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
                            'model_attribute' => 'kritikere',
                            'person_role' => 'kritiker',
                            'search' => [
                                'type' => 'autocomplete',
                                'placeholder' => 'Fornavn og/eller etternavn',
                                'options' => [],
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
                            'help' => 'Kryss av hvis det er flere personer som ikke er listet opp',
                            'default' => false,
                            'display' => false,
                            'search' => false,
                        ],

                        // Publikasjon
                        [
                            'key' => 'publikasjon',
                            'type' => 'autocomplete',
                            'search' => [
                                'placeholder' => 'Publikasjon',
                                'index' => [
                                    'type' => 'simple',
                                    'column' => 'publikasjon',
                                ],
                            ],
                        ],

                        // Type
                        [
                            'key' => 'kritikktype',
                            'type' => 'tags',
                            'default' => [],
                            'search' => [
                                'type' => 'autocomplete',
                                'placeholder' => 'F.eks. teaterkritikk, forfatterportrett, ...',
                                'index' => [
                                    'type' => 'array',
                                    'column' => 'kritikktype',
                                ],
                            ],
                        ],

                        // År
                        [
                            'key' => 'dato',
                            'type' => 'simple',
                            'display' => [
                                'columnClassName' => 'dt-body-nowrap',
                            ],
                            'search' => [
                                'type' => 'rangeslider',
                                'options' => [
                                    'minValue' => (int) $minYear,
                                    'maxValue' => (int) $maxYear,
                                ],
                                'index' => [
                                    'type' => 'range',
                                    'column' => 'aar_numeric',
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
                        ],
                        [
                            'key' => 'bind',
                            'type' => 'simple',
                        ],
                        [
                            'key' => 'hefte',
                            'type' => 'simple',
                        ],
                        [
                            'key' => 'nummer',
                            'type' => 'simple',
                        ],
                        [
                            'key' => 'sidetall',
                            'type' => 'simple',
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
                            'search' => [
                                'advanced' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($grouped['fields'] as &$field) {
            $field['label'] = trans('litteraturkritikk.' . $field['key']);
        }
        foreach ($grouped['groups'] as &$group) {
            foreach ($group['fields'] as &$field) {

                // Add label
                $field['label'] = trans('litteraturkritikk.' . $field['key']);

                // Add default operators if not set
                if (isset($field['search']) && $field['search'] && !isset($field['search']['operators'])) {
                    $field['search']['operators'] = [
                        'eq',
                        'neq',
                        'isnull',
                        'notnull',
                    ];
                }
            }
        }

        return $grouped;
    }

    public static function getSearchFields(): array
    {
        return self::getColumns();


    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'litteraturkritikk_records';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'kritikktype' => 'array',
    ];

    public static function getColumnsFlatList()
    {
        $columns = self::getColumns();
        $out = [];
        foreach ($columns['fields'] as &$field) {
            $out[] = $field;
        }
        foreach ($columns['groups'] as &$group) {
            foreach ($group['fields'] as &$field) {
                $out[] = $field;
            }
        }
        return $out;
    }

    public static function getColumnsFlatListByKey()
    {
        $columns = self::getColumnsFlatList();
        $out = [];
        foreach ($columns as &$field) {
            $out[$field['key']] = $field;
        }
        return $out;
    }

    public static function getColumnKeys()
    {
        return array_map(function($col) {
            return $col['key'];
        }, self::getColumnsFlatList());
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }

    /**
     * The persons that are part of this record.
     */
    public function persons()
    {
        return $this->belongsToMany('App\Litteraturkritikk\Person', 'litteraturkritikk_record_person', 'record_id', 'person_id')
            ->withPivot('person_role', 'kommentar', 'pseudonym');
    }

    /**
     * The persons that are part of this record.
     */
    public function forfattere()
    {
        return $this->persons()
            ->where('person_role', '!=', 'kritiker');
    }

    /**
     * The persons that are part of this record.
     */
    public function kritikere()
    {
        return $this->persons()
            ->where('person_role', '=', 'kritiker');
    }

    public function formatKritikkType($name)
    {
        if ($name == 'debatt') {
            $name = 'Debatt';
        }
        return ucfirst($name);
    }

    public function preferredKritikktype($types)
    {
        $preferredTypes = [
            'forfatterportrett',
            'dagskritikk',
            'teaterkritikk',
            'debatt',
            'essay',
            'bokanmeldelse',
            'artikkel',
            'vitenskapelig artikkel',
            'oversiktsartikkel',
            'samtaleprogram',
        ];
        foreach ($preferredTypes as $key) {
            if (in_array($key, $types)) {
                return $key;
            }
        }

        return '';
    }

    public function kritikktypeSkilleord($preferredType)
    {
        $separators = [
            'forfatterportrett' => 'av',
            'dagskritikk' => 'av',
            'teaterkritikk' => 'av',
            'bokanmeldelse' => 'av',
            'debatt' => 'om',
            'artikkel' => 'om',
            'oversiktsartikkel' => 'om',
            'vitenskapelig artikkel' => 'om',
            'essay' => 'om',
            'samtaleprogram' => 'om',
        ];

        return isset($separators[$preferredType]) ? $separators[$preferredType] : 'av/om';
    }

    public function formatVerk()
    {
        $forfatter_delimiter = '; ';
        $forfatter_verk_delimiter = '. ';

        $repr = '';
        $forfattere = [];
        foreach ($this->forfattere as $person) {
            $forfattere[] = strval($person) . (($person->pivot->person_role != 'forfatter') ? ' (' . $person->pivot->person_role . ')' : '');
        }

        $forfattere = implode($forfatter_delimiter, $forfattere);

        $repr .= $forfattere;

        if ($forfattere && $this->verk_tittel) {
            $repr .= $forfatter_verk_delimiter;
        }
        if ($this->verk_tittel) {
            $repr .= '«' . $this->verk_tittel . '»';
        }
        if ($this->verk_dato) {
            $repr .= ' (' . $this->verk_dato . ')';
        }

        return $repr;
    }

    public function formatKritikk()
    {
        $repr = '';

        $kritikere = [];
        foreach ($this->kritikere as $person) {
            $kritikere[] = strval($person);
        }

        $kritikere = implode(', ', $kritikere);

        $repr .= $kritikere ?: 'ukjent';

        if ($this->dato) {
            $repr .= ' (' . $this->dato . ')';
        }

        if (strlen($repr) && $this->tittel) {
            $repr .= '. ';
        }

        if ($this->tittel) {
            $repr .= '«' . $this->tittel . '»';
        }

        if (strlen($repr) && $this->publikasjon) {
            $repr .= '. ';
        }

        if ($this->publikasjon) {
            $repr .= '<em>' . $this->publikasjon . '</em>';
        }
        if ($this->bind) {
            $repr .= ' bd. ' . $this->bind . '';
        }
        if ($this->nummer) {
            $repr .= ' nr. ' . $this->nummer . '';
        }

        if (strlen($repr)) {
            $repr .= '.';
        }

        return $repr;
    }

    public function representation()
    {
        $repr = '<a href="' . action('LitteraturkritikkController@show', $this->id) . '">';

        $repr .= $this->formatKritikk();
        $kritikktype = $this->preferredKritikktype($this->kritikktype);
        $verk = $this->formatVerk();

        $repr .= '</a><br>';

        $repr .= ' ' . $this->formatKritikkType($kritikktype);

        if ($kritikktype && $verk) {
            $repr .= ' ' . $this->kritikktypeSkilleord($kritikktype) . ': ';
        } elseif ($verk) {
            $repr .= 'Om: ';
        }

        $repr .= $verk;

        return $repr;
    }
}
