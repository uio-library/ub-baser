<?php

namespace App\Litteraturkritikk;

use Illuminate\Database\Eloquent\SoftDeletes;

class Record extends \App\Record
{
    use SoftDeletes;

    public static $prefix = 'litteraturkritikk';

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
        return $this->belongsToMany(
            'App\Litteraturkritikk\Person',
            'litteraturkritikk_record_person',
            'record_id',
            'person_id'
        )->withPivot('person_role', 'kommentar', 'pseudonym');
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
            $personRole = ($person->pivot->person_role != 'forfatter') ? ' (' . $person->pivot->person_role . ')' : '';
            $forfattere[] = strval($person) . $personRole;
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

    public function isNewspaper()
    {
        return Publications::isNewspaper($this->publikasjon);
    }

    public function isJournal()
    {
        return Publications::isJournal($this->publikasjon);
    }

    public function oriaSearchLink(): string
    {
        // Oria syntax is of course weird and non-standard to the point
        // that we cannot use a single http_build_query call.

        $queries = [];
        $forfatter = $this->forfattere()->first();
        if ($forfatter !== null) {
            $queries[] = http_build_query(['query' => "creator,contains,{$forfatter->etternavn}"]);
        }
        if ($this->verk_tittel) {
            $queries[] = http_build_query(['query' => "title,contains,{$this->verk_tittel}"]);
        }

        $queries[] = http_build_query([
            'tab' => 'default_tab',
            'search_scope' => 'default_scope',
            'vid' => 'UIO',
            'mode' => 'advanced',
        ]);

        return implode('&', $queries);
    }

    public function nationalLibrarySearchLink(string $group): string
    {
        $query = ['q' => []];


        if ($group == 'Kritikken') {

            $dato = $this->dato;

            if ($this->isNewspaper()) {
                $query['mediatype'] = 'aviser';
                $query['series'] = $this->publikasjon;
            } elseif ($this->isJournal()) {
                $query['mediatype'] = 'tidsskrift';
                $query['title'] = $this->publikasjon;
            }

        } else {

            $dato = $this->verk_dato;

            $query['mediatype'] = 'bøker';

            if ($this->verk_tittel) {
                $query['title'] = $this->verk_tittel;
            }

        }

        if ($dato && strlen($dato) <= 10) {
            $query['fromDate'] = str_replace('-', '', $dato);
            $query['toDate'] = str_replace('-', '', $dato);
            if (strlen($dato) == 4) {
                $query['fromDate'] = "{$dato}0101";
                $query['toDate'] = "{$dato}1231";
            }
        }

        if ($group == 'Kritikken') {

            if ($this->nummer) {
                $query['q'][] = $this->nummer;
            }

            $kritiker = $this->kritikere()->first();
            if ($kritiker !== null && !$kritiker->pivot->pseudonym && !preg_match('/(anonym|ukjent)/', $kritiker->etternavn)) {
                $query['q'][] = $kritiker->etternavn;
            }
            $forfatter = $this->forfattere()->first();
            if ($forfatter !== null) {
                $query['q'][] = $forfatter->etternavn;
            }

        } else {
            $forfatter = $this->forfattere()->first();
            if ($forfatter !== null) {
                $query['name'] = $forfatter->etternavn;
            }
        }


        $query['q'] = implode(' ', $query['q']);

        return http_build_query($query);
    }
}
