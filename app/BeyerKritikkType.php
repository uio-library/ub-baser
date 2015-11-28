<?php

namespace App;

class BeyerKritikkType extends \Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'beyer_kritikktyper';

    public function __toString()
    {
        return $this->navn;
    }
}
