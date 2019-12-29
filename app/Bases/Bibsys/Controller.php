<?php

namespace App\Bases\Bibsys;

use App\Base;
use App\Http\Controllers\BaseController as BaseController;
use App\Http\Request;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    protected $logGroup = 'bibsys';

    static public $defaultColumns = [
        'dokid',
        'avdeling',
        'samling',
        'hyllesignatur',
        'title_statement',
        'pub_date',
    ];

    static public $defaultSortOrder = [
    ];

    /**
     * Show a record.
     *
     * @param Request $request
     * @param Base $base
     * @param $id
     * @return Response
     */
    public function show(Request $request, Base $base, $id)
    {
        $record = $base->getRecord($id, false);
        if (is_null($record)) {
            abort(404, trans('base.error.recordnotfound'));
        }

        $record->marc_record_formatted = preg_replace(
            ['/^\*/m', '/\n/', '/\$([a-z0-9])/'],
            ['', '<br>', '<span style="font-weight: bold">$\1 </span>'],
            $record->marc_record
        );

        return response()->view($base->getView('show'), [
            'title' => $record->getTitle(),
            'base' => $base,
            'schema' => $base->getSchema(),
            'record'  => $record,
        ]);
    }

}
