<?php

namespace App\Bases;

class Config
{
    public $costLimit = null;

    public function get($key) {
        return $this->{$key};
    }
}
