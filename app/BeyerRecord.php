<?php

namespace App;

class BeyerRecord extends Record
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'litteraturkritikk';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'kritikktype' => 'array',
        'verk_spraak' => 'array',
    ];

    public function formatKritikktype($x)
    {
        if (in_array('forfatterportrett', $x)) {
            return 'Forfatterportrett';
        } elseif (in_array('dagskritikk', $x)) {
            return 'Dagskritikk';
        } elseif (in_array('teaterkritikk', $x)) {
            return 'Teaterkritikk';
        } elseif (in_array('debatt', $x)) {
            return 'Debattinnlegg';
        } elseif (in_array('artikkel', $x)) {
            return 'Artikkel';
        }

        return 'Kritikk';
    }

    public function formatKritikktypeSkilleord($x)
    {
        if (in_array('forfatterportrett', $x)) {
            return 'av';
        } elseif (in_array('dagskritikk', $x)) {
            return 'av';
        } elseif (in_array('teaterkritikk', $x)) {
            return 'av';
        } elseif (in_array('debatt', $x)) {
            return 'om';
        } elseif (in_array('artikkel', $x)) {
            return 'om';
        }

        return 'av';
    }

    public function formatForfatter($etternavn, $fornavn, $kommentar)
    {
        $repr = '';
        if ($etternavn) {
            $repr .= $fornavn . ' ' . $etternavn;
            if ($kommentar) {
                $repr .= ' (' . $kommentar . ')';
            }
        }

        return $repr;
    }

    public function formatVerk()
    {
        $repr = '';

        $forfatter = $this->formatForfatter($this->forfatter_etternavn, $this->forfatter_fornavn, $this->forfatter_kommentar);
        $repr .= $forfatter;
        if ($forfatter && $this->verk_tittel) {
            $repr .= ': ';
        }
        if ($this->verk_tittel) {
            $repr .= '«' . $this->verk_tittel . '»';
        }
        if ($this->verk_aar) {
            $repr .= ' (' . $this->verk_aar . ')';
        }

        return $repr;
    }

    public function formatKritikk()
    {
        $repr = '';
        $kritiker = $this->formatForfatter($this->kritiker_etternavn, $this->kritiker_fornavn, '');
        $repr .= $kritiker ?: 'ukjent';

        if ($this->aar) {
            $repr .= ' (' . $this->aar . ')';
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
        $repr = $this->formatKritikk();
        $kritikktype = $this->formatKritikktype($this->kritikktype);
        $verk = $this->formatVerk();

        $repr .= ' ' . $kritikktype;

        if ($kritikktype && $verk) {
            $repr .= ' ' . $this->formatKritikktypeSkilleord($this->kritikktype) . ': ';
        }

        $repr .= $verk;

        return $repr;
    }
}
