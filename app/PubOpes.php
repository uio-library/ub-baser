<?php

namespace App;

class PubOpes extends \Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'opes_pub';

    /**
     * Get the records from this publication.
     */
    // peker tilbake til papyrus item
    public function papyrus()
    {
        return $this->belongsTo('App\OpesRecord');
    }

    public function Ser_vol()
    {
        return $this->Ser_vol;
    }

    public function Editor()
    {
        return $this->Editor;
    }

    public function Year()
    {
        return $this->Year;
    }

    public function __toString()
    {
        return $this->Ser_vol;
    }
}
