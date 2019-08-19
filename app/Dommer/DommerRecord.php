<?php

namespace App\Dommer;

use App\Record;

class DommerRecord extends Record
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dommer';

    /**
     * Get the periodical this record belongs to.
     */
    public function kilde()
    {
        return $this->belongsTo('App\Dommer\DommerKilde');
    }

    public function link()
    {
        return action('DommerController@show', $this->id);
    }
}