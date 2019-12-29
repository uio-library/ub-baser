<?php

namespace App\Bases\Dommer;

class DommerKilde extends \Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dommer_kilder';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the records from this publication.
     */
    public function poster()
    {
        return $this->hasMany(Record::class, 'kilde_id');
    }

    public function __toString()
    {
        return $this->navn;
    }
}
