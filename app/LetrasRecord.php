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

    public function forfatter()
    {
     
      $repr = $this->forfatter;
        //
     return $repr;

      //$repr = '<a href="' . action('LetrasController@show', $this->id) . '">';

      //return $repr;
     //$repr = $this->forfatter;
        //
     //return action('LetrasController@show', $this->id);
    }

     public function tittel()
    {
     
      $repr = $this->tittel;
        //
     return $repr;
    }

     public function utgivelsesaar()
    {
     
      $repr = $this->utgivelsesaar;
        //
     return $repr;
    }
    public function sjanger()
    {
     
      $repr = $this->sjanger;
        //
     return $repr;
    }
}
