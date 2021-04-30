<?php

namespace App\Bases\Bibliofremmedspraak;

use App\Http\Controllers\BaseController;

class Controller extends BaseController
{
    protected $logGroup = 'bibliofremmedspraak';

    public static $defaultColumns = [
        // Verket
        'forfatter',
        'tittel',
        'antologi',

        // Oversettelsen
        'utgiver',
        'utgivelsesaar',
        'type',
    ];

    public static $defaultSortOrder = [
        ['key' => 'utgivelsesaar', 'direction' => 'desc'],
    ];
}
