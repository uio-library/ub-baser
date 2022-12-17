<?php

namespace App\Bases\Bibsys;

use App\Http\Controllers\BaseController;

class Controller extends BaseController
{
    protected $logGroup = 'bibsys';

    public static $defaultColumns = [
        'dokid',
        'avdeling',
        'samling',
        'hyllesignatur',
        'title_statement',
        'pub_date',
    ];

    public static $defaultSortOrder = [
    ];
}
