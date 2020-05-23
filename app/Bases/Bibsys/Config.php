<?php

namespace App\Bases\Bibsys;

use App\Bases\Config as BaseConfig;

class Config extends BaseConfig
{
    public $costLimit = 20000;

    public $classBindings = [
        'Record' => BibsysView::class,
        'RecordView' => BibsysView::class,
    ];

    /**
     * @var bool Whether this base use soft deletes.
     */
    public $softDeletes = false;
}
