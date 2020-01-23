<?php

namespace App\Bases\Letras;

use App\Http\Controllers\BaseController;

class Controller extends BaseController
{
    protected $logGroup = 'letras';

    public static $defaultColumns = [
        // Verket
        'forfatter',
        'tittel',
        'utgivelsesaar',

        // Oversettelsen
        'oversetter',
        'tittel2',
        'utgivelsesaar2',
    ];

    public static $defaultSortOrder = [
        ['key' => 'utgivelsesaar', 'direction' => 'desc'],
    ];
}
