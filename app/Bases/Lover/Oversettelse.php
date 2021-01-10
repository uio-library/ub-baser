<?php

namespace App\Bases\Lover;

use App\Record as BaseRecord;
use Illuminate\Database\Eloquent\SoftDeletes;

class Oversettelse extends BaseRecord
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'oversatte_lover_oversettelser';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['string_representation'];

    /**
     * Get the act this record belongs to.
     */
    public function lov()
    {
        return $this->belongsTo(Lov::class);
    }

    public function link()
    {
        return action([Controller::class, 'show'], $this->id);
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        // To simplify editing, we cast these as strings
        // 'side' => 'string',
        // 'aar' => 'string',
    ];

    /**
     * Get a title for this record that can be used in the <title> element etc.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->sprak . ': ' . ($this->kort_tittel ?: $this->tittel) ;
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
