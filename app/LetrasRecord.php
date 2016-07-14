<?php

namespace App;

class LetrasRecord extends Record
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'letras';

    public function representation()
    {
     
     $repr = $this->forfatter;
        //
     return $repr;
    }
}
