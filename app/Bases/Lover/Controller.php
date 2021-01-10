<?php

namespace App\Bases\Lover;

use App\Http\Controllers\BaseController as BaseController;

class Controller extends BaseController
{
    protected $logGroup = 'oversatte_lover';

    /* TODO: Default columns to show in table view */
    public static $defaultColumns = [
        'tittel',
        'sprak',
        'lov_tittel',
        'lov_dato',
        'lov_nummer',
    ];

    public static $defaultSortOrder = [
        ['key' => 'lov_dato', 'direction' => 'asc'],
    ];
}
