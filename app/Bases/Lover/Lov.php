<?php

namespace App\Bases\Lover;

use App\Record as BaseRecord;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lov extends BaseRecord
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'oversatte_lover_lover';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['string_representation'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        // To simplify editing, we cast these as strings
    ];

    /**
     * Get a title for this record that can be used in the <title> element etc.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->kort_tittel ?: '#' . $this->id;
    }

    /**
     * Get the translations of this act.
     */
    public function oversettelser()
    {
        return $this->hasMany(
            Oversettelse::class,
            'lov_id'
        ); //->orderBy('oversatte_lover.sprak', 'asc');
    }

    /**
     * Simple string representation, for use in entity editor.
     *
     * @return string
     */
    public function getStringRepresentationAttribute()
    {
        return $this->getTitle();
    }

    public function __toString()
    {
        return $this->getStringRepresentationAttribute();
    }

}
