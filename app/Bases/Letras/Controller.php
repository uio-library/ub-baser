<?php

namespace App\Bases\Letras;

use App\Http\Controllers\BaseController;

class Controller extends BaseController
{
    protected $logGroup = 'letras';

    static public $defaultColumns = [
        // Verket
        'forfatter',
        'tittel',
        'utgivelsesaar',

        // Oversettelsen
        'oversetter',
        'tittel2',
        'utgivelsesaar2',
    ];

    static public $defaultSortOrder = [
        ['key' => 'utgivelsesaar', 'direction' => 'desc'],
    ];
}
