<?php

namespace App\Bases\Lover;

use App\Base;
use App\Http\Controllers\BaseController;
use App\Http\Requests\SearchRequest;
use Illuminate\Http\Response;

class LovController extends BaseController
{
    protected $logGroup = 'oversatte-lover';
    protected $recordClass = 'Lov';
    protected $recordSchema = 'LovSchema';
    protected $editView = 'lov.edit';

    /**
     * Show a translation record.
     *
     * @param SearchRequest $request
     * @param Base $base
     * @param $id
     * @return Response
     */
    public function show(SearchRequest $request, Base $base, $id)
    {
        $schema = app(LovSchema::class);
        $record = Lov::withTrashed()->with(
            'oversettelser',
        )->findOrFail($id);

        return response()->view($base->getView('lov.show'), [
            'base' => $base,
            'schema' => $schema,
            'title' => $record->getStringRepresentationAttribute(),
            'record' => $record,
        ]);
    }
}
