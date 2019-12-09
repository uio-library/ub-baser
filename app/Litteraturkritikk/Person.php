<?php

namespace App\Litteraturkritikk;

use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends \Eloquent
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'litteraturkritikk_personer';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['etternavn', 'fornavn', 'kjonn', 'fodt', 'dod', 'bibsys_id', 'wikidata_id', ];

    /**
     * The records this person belongs to.
     */
    public function records()
    {
        return $this->belongsToMany(
            'App\Litteraturkritikk\Record',
            'litteraturkritikk_record_person',
            'person_id',
            'record_id'
        )->withPivot('person_role', 'kommentar', 'pseudonym', 'posisjon');
    }

    public function recordsAsAuthor()
    {
        return $this->records()
            ->where('person_role', '!=', 'kritiker');
    }

    public function recordsAsCritic()
    {
        return $this->records()
            ->where('person_role', '=', 'kritiker');
    }

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
            $nn .= ', '.$this->fodt.'-'.($this->dod ?: '');
        }

        return $nn;
    }

    public function __toString()
    {
        return $this->normalizedName();
    }

    public function kjonnRepr()
    {
        if ($this->kjonn == 'm') {
            return 'mann';
        }
        if ($this->kjonn == 'f') {
            return 'kvinne';
        }
        if ($this->kjonn == 'u') {
            return 'ukjent';
        }
        return '';
    }
}
