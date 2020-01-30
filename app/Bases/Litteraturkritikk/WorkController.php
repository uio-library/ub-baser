<?php

namespace App\Bases\Litteraturkritikk;

use App\Base;
use App\Http\Controllers\BaseController;
use App\Http\Request;
use App\Services\QueryStringBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class WorkController extends BaseController
{
    protected $logGroup = 'norsk-litteraturkritikk';
    protected $recordClass = 'Work';
    protected $recordSchema = 'WorkSchema';
    protected $showView = 'works.show';
    protected $editView = 'works.edit';

    /**
     * Show a work record.
     *
     * @param Request $request
     * @param Base $base
     * @param $id
     * @return Response
     */
    public function show(Request $request, Base $base, $id)
    {
        $schema = $base->make($this->recordSchema);
        $record = $base->getRecord($id, true, $this->recordClass);

        return response()->view($base->getView($this->showView), [
            'base' => $base,
            'schema' => $schema,
            'record' => $record,
            'queryStringBuilder' => new QueryStringBuilder($base->getSchema()),
        ]);
    }

    /**
     * Store a new work record.
     *
     * @param Request $request
     * @param Base $base
     * @return JsonResponse
     */
    public function store(Request $request, Base $base)
    {
        $record = $base->newRecord($this->recordClass);

        $this->validate($request, $this->getValidationRules($record));

        $this->updateOrCreateRecord($record, $base->getSchema($this->recordSchema), $request);

        $record->save();

        $url = $base->action('WorkController@show', $record->id);
        $this->log(
            'Opprettet <a href="%s">verk #%s</a>.',
            $url,
            $record->id
        );

        return response()->json([
            'status' => 'ok',
            'record' => $record,
        ]);
    }
}
