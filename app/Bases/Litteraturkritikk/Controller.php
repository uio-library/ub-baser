<?php

namespace App\Bases\Litteraturkritikk;

use App\Base;
use App\Exceptions\HttpErrorResponse;
use App\Exceptions\NationalLibraryRecordNotFound;
use App\Http\Controllers\BaseController;
use App\Http\Request;
use App\Record;
use App\Services\NationalLibraryApi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Controller extends BaseController
{
    protected $logGroup = 'norsk-litteraturkritikk';

    public static $defaultColumns = [
        // Verket
        'verk_tittel',
        'verk_forfatter',
        'verk_dato',

        // Kritikken
        'kritiker',
        'publikasjon',
        'dato',
    ];

    public static $defaultSortOrder = [
        ['key' => 'dato', 'direction' => 'asc'],
    ];

    /**
     * Store a newly created record, or update an existing one.
     *
     * @param Base $base
     * @param Model $record
     * @param Request $request
     * @throws ValidationException
     * @return array
     */
    protected function updateOrCreateRecord(Base $base, Record $record, Request $request): array
    {
        $schema = $base->getSchema($record);

        // Update the main record
        try {
            $changes = parent::updateOrCreateRecord($base, $record, $request);
        } catch (NationalLibraryRecordNotFound $ex) {
            throw ValidationException::withMessages([
                $ex->field => ['Klarte ikke å slå opp URL-en: ' . $ex->url],
            ]);
        }

        return $changes;
    }

    protected function nationalLibrarySearch(
        Request $request,
        NationalLibraryApi $api
    ) {
        // Note: Because of the repeated filter= statements, we need to get the unprocessed query string
        $query = $request->server->get('QUERY_STRING');
        $recordId = $request->server->get('recordId');

        try {
            return response()->json($api->search($query));
        } catch (HttpErrorResponse $ex) {
            \Log::info(
                '[Post ' . htmlspecialchars($recordId) . '] ' .
                'NB-søk lot seg ikke utføre: "' . htmlspecialchars($query) . '"'
            );
            return response()->json(['error' => $ex], $ex->response->getStatusCode());
        }
    }

    protected function listIndex(Base $base, Request $request, AggregateLists $lists)
    {
        return view('litteraturkritikk.lists.index', [
            'lists' => $lists->all(),
        ]);
    }

    protected function listShow(Base $base, Request $request, AggregateLists $lists, $id)
    {
        $list = $lists->get($id);

        if (is_null($list)) {
            abort(404, 'List not found');
        }

        $sort = $request->get('sort', 'record_count');

        return view('litteraturkritikk.lists.show', [
            'list' => $list,
            'sort' => $sort,
            'aggs' => $list->getResults($sort),
        ]);
    }
}
