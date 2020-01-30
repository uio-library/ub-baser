<?php

namespace App\Bases\Litteraturkritikk;

use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends \App\Record
{
    use SoftDeletes;

    /**
     * Short name used to determine routes etc.
     *
     * @var string
     */
    public static $shortName = 'person';

    /**
     * Schema class used with this model.
     *
     * @var string
     */
    public static $schema = PersonSchema::class;

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
    protected $fillable = ['etternavn', 'fornavn', 'kjonn', 'fodt', 'dod', 'bibsys_id', 'wikidata_id'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['string_representation'];

    /**
     * Records this person has created.
     */
    public function records()
    {
        return $this->morphedByMany(
            Record::class,
            'contribution',
            'litteraturkritikk_person_contributions'
        )
            ->using(PersonContribution::class)
            ->withPivot('person_role', 'kommentar', 'pseudonym', 'position');
    }

    /**
     * Works this person has created or contributed to.
     */
    public function works()
    {
        return $this->morphedByMany(
            Work::class,
            'contribution',
            'litteraturkritikk_person_contributions'
        )
            ->withPivot('person_role', 'kommentar', 'pseudonym', 'position');
    }

    /**
     * The records that discusses this author.
     */
    public function discussedIn()
    {
        return $this->belongsToMany(Record::class, 'litteraturkritikk_subject_person');
    }

    /*public function recordsAsAuthor()
    {
        return $this->records()
            ->whereJsonDoesntContain('person_role', 'kritiker');
    }*/

    public function recordsAsCritic()
    {
        return $this->records()
            ->whereJsonContains('person_role', 'kritiker');
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
        // if ($this->fodt !== null) {
        //     $nn .= ', ' . $this->fodt . '-' . ($this->dod ?: '');
        // }
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

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return $this->normalizedName();
    }
}
