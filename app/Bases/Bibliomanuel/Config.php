<?php

namespace App\Bases\Bibliomanuel;

use App\Bases\Config as BaseConfig;

class Config extends BaseConfig
{
    public $classBindings = [
        'RecordView' => 'Record',
    ];
}
