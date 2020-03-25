<?php

namespace App\Bases;

class Config
{
    public $costLimit = null;

    public $resultsComponent = 'data-table';

    public $classBindings = [
    ];

    public function get($key)
    {
        return $this->{$key};
    }

    public function getClassBinding($name)
    {
        return $this->classBindings[$name] ?? $name;
    }
}
