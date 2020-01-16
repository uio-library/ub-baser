<?php

namespace App\Bases\Opes;

class Publication extends \Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'opes_publications';

    /**
     * Get the record the publication belongs to.
     */
    public function record()
    {
        return $this->belongsTo(Record::class, 'opes_id');
    }
}
