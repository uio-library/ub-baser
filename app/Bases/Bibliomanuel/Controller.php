<?php

namespace App\Bases\Bibliomanuel;

use App\Http\Controllers\BaseController;

class Controller extends BaseController
{
    protected $logGroup = 'bibliomanuel';

    public static $defaultColumns = [
        // Verket
        'forfatter',
        'tittel',
        'antologi',

        // Oversettelsen
        'utgiver',
        'aar',
        'type',
    ];

    public static $defaultSortOrder = [
        ['key' => 'aar', 'direction' => 'desc'],
    ];
}
