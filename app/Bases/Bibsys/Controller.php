<?php

namespace App\Bases\Bibsys;

use App\Base;
use App\Http\Controllers\BaseController as BaseController;
use App\Http\Requests\SearchRequest;
use Illuminate\Http\Response;

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
