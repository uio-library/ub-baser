<?php

namespace App\Bases\Letras;

use App\Bases\Config as BaseConfig;

class Config extends BaseConfig
{
    public $classBindings = [
        'RecordView' => 'Record',
    ];
}
