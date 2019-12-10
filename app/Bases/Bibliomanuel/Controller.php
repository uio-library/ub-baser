<?php

namespace App\Bases\Bibliomanuel;

use App\Http\Controllers\BaseController;

class Controller extends BaseController
{
    protected $logGroup = 'bibliomanuel';

    static public $defaultColumns = [
        // Verket
        'forfatter',
        'tittel',
        'antologi',

        // Oversettelsen
        'utgiver',
        'aar',
        'type',
    ];

    static public $defaultSortOrder = [
        ['key' => 'aar', 'direction' => 'desc'],
    ];
}
