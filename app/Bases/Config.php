<?php

namespace App\Bases;

class Config
{
    public $costLimit = null;

    public $resultsComponent = 'data-table';

    public function get($key) {
        return $this->{$key};
    }
}
