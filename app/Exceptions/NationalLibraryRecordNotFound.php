<?php

namespace App\Exceptions;

use Exception;

class NationalLibraryRecordNotFound extends Exception
{
    public $url;
    public $field;

    public function __construct($url, $field = null)
    {
        parent::__construct();

        $this->url = $url;
        $this->field = $field;
    }
}
