<?php

namespace App\Bases\Litteraturkritikk;

use App\Record as BaseRecord;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends BaseRecord
{
    use SoftDeletes;
    use HasFactory;

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
    protected $casts = ['deleted_at' => 'datetime'];

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
     * The records this person belongs to.
     */
    public function records()
    {
        return $this->belongsToMany(
            'App\Bases\Litteraturkritikk\Record',
            'litteraturkritikk_record_person',
            'person_id',
            'record_id'
        )
            ->using(RecordPerson::class)
            ->withPivot('person_role', 'kommentar', 'pseudonym', 'posisjon');
    }

    public function recordsAsAuthor()
    {
        return $this->records()
            ->whereJsonDoesntContain('person_role', 'kritiker');
    }

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

    public function getTitle(): string
    {
        return $this->getStringRepresentationAttribute();
    }
}
