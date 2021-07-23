<?php

namespace App\Bases\Nordskrift;

use App\Http\Controllers\BaseController;

class Controller extends BaseController
{
    protected $logGroup = 'nordskrift';

    public static $defaultColumns = [
        // Verket
        'forfatter',
        'tittel',
        'utgivelsessted',
    ];

    public static $defaultSortOrder = [
        ['key' => 'dato', 'direction' => 'desc'],
    ];
}
