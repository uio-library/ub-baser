<?php

namespace App\Bases\Bibliofremmedspraak;

use App\Bases\AutocompleteService;
use App\Bases\Config as BaseConfig;

class Config extends BaseConfig
{
    public $classBindings = [
        'AutocompleteService' => AutocompleteService::class,
        'RecordView' => 'Record',
    ];
}
