<?php

namespace App\Litteraturkritikk;

class KritikkType extends \Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'litteraturkritikk_kritikktyper';

    public function __toString()
    {
        return $this->navn;
    }
}
