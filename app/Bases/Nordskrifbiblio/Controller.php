<?php

namespace App\Bases\Nordskrifbiblio;

use App\Http\Controllers\BaseController;

class Controller extends BaseController
{
    protected $logGroup = 'nordskrifbiblio';

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
