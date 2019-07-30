<?php

namespace App\Litteraturkritikk;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

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
