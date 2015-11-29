<?php

namespace App;

class BeyerRecord extends Record
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'beyer';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'kritikktype' => 'array',
        'verk_spraak' => 'array',
    ];

    public function representation()
    {
        $repr = '';
        if ($this->forfatter_etternavn) {
            $repr .= $this->forfatter_fornavn . ' ' . $this->forfatter_etternavn;
        }
        if ($this->verk_tittel) {
            $repr .= ': «' . $this->verk_tittel . '»';
        }
        if ($this->verk_aar) {
            $repr .= ' (' . $this->verk_aar . ')';
        }
        if (strlen($repr)) {
            $repr .= '.';
        }

        if (in_array('forfatterportrett', $this->kritikktype)) {
            $repr .= ' Forfatterportrett';
        } elseif (in_array('dagskritikk', $this->kritikktype)) {
            $repr .= ' Dagskritikk';
        } elseif (in_array('teaterkritikk', $this->kritikktype)) {
            $repr .= ' Teaterkritikk';
        } elseif (in_array('debatt', $this->kritikktype)) {
            $repr .= ' Debattinnlegg';
        } elseif (in_array('artikkel', $this->kritikktype)) {
            $repr .= ' Artikkel';
        } else {
            $repr .= ' Kritikk';
        }
        if ($this->tittel) {
            $repr .= ' «' . $this->tittel . '»';
        }
        if ($this->kritiker_fornavn) {
            $repr .= ' av ' . ($this->kritiker_fornavn ? $this->kritiker_fornavn . ' ' . $this->kritiker_etternavn : '<em>ukjent kritiker</em>');
        }
        if ($this->publikasjon) {
            $repr .= ' i  <em>' . $this->publikasjon . '</em>';
        }
        $repr .= $this->aar ? ' (' . $this->aar . ')' : '';
        if ($this->bind) {
            $repr .= ' bd. ' . $this->bind . '';
        }
       if ($this->nummer) {
            $repr .= ' nr. ' . $this->nummer . '';
        }

        return $repr;
    }
}
