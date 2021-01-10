<?php

namespace App\Bases\Lover;

use App\Bases\Config as BaseConfig;

class Config extends BaseConfig
{
    public $classBindings = [
        'AutocompleteService' => AutocompleteService::class,
        'Record' => Oversettelse::class,
    ];
}
