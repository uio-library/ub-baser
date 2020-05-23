<?php

namespace App\Bases;

class Config
{
    public $costLimit = null;

    public $resultsComponent = 'data-table';

    public $classBindings = [
    ];

    public function get($key, $default = null)
    {
        return isset($this->{$key}) ? $this->{$key} : $default;
    }

    public function getClassBinding($name)
    {
        return $this->classBindings[$name] ?? $name;
    }
}
