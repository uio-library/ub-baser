<?php

namespace App\Bases\Litteraturkritikk;

class PersonView extends Record
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'litteraturkritikk_personer_view';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['string_representation'];

    public function normalizedName()
    {
        $names = [];
        if ($this->etternavn) {
            $names[] = $this->etternavn;
        }
        if ($this->fornavn) {
            $names[] = $this->fornavn;
        }
        $nn = implode(', ', $names);
        if ($this->fodt !== null) {
            $nn .= ', ' . $this->fodt . '-' . ($this->dod ?: '');
        }

        return $nn;
    }

    public function getStringRepresentationAttribute()
    {
        return $this->normalizedName();
    }

    public function __toString()
    {
        return $this->normalizedName();
    }
}
