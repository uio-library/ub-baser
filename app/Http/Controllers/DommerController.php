<?php

namespace App\Http\Controllers;

use App\Dommer\DommerRecord;
use App\Dommer\DommerSchema;
use App\Http\Requests\DommerSearchRequest;
use App\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DommerController extends RecordController
{
    protected $logGroup = 'dommer';

    /**
     * Display a listing of the resource.
     *
     * @param DommerSearchRequest $request
     * @param DommerSchema $schema
     * @return Response
     */
    public function index(DommerSearchRequest $request, DommerSchema $schema)
    {
        if ($request->wantsJson()) {
            return $this->dataTablesResponse($request, $schema);
        }

        $introPage = Page::where('slug', '=', 'dommer/intro')->first();
        $intro = $introPage ? $introPage->body : '';

        return response()->view('dommer.index', [
            'schema' => $schema,

            'query' => $request->all(),
            'processedQuery' => $request->queryParts,
            'advancedSearch' => ($request->advanced === 'on'),

            'intro' => $intro,

            'defaultColumns' => [
                'navn',
                'kilde',
                'aar',
                'side',
            ],
            'order' => [
                ['key' => 'aar', 'direction' => 'desc'],
            ]
        ]);
    }

    /**
     * Store a newly created record, or update an existing one.
     *
     * @param Request $request
     * @param DommerSchema $schema
     * @param DommerRecord $record
     */
    protected function updateOrCreate(Request $request, DommerSchema $schema, DommerRecord $record)
    {
        // Validate
        $this->validate($request, [
            'navn'     => 'required|unique:dommer,navn' . ($record->id === null ? '' : ',' . $record->id) . '|max:255',
            'aar'      => 'required|digits:4',
            'side'     => 'required|numeric|min:1|max:9999',
            'kilde'    => 'required|numeric',
        ]);

        // Update
        $this->updateRecord($schema, $record, $request);
        // $record->kilde_id = $request->get('kilde_id');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param DommerSchema $schema
     * @return Response
     */
    public function create(DommerSchema $schema)
    {
        $this->authorize('dommer');

        $data = $this->formArguments(new DommerRecord(), $schema);

        return response()->view('dommer.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param DommerSchema $schema
     * @return Response
     */
    public function store(Request $request, DommerSchema $schema)
    {
        $this->authorize('dommer');

        $record = new DommerRecord();
        $this->updateOrCreate($request, $schema, $record);

        $this->log(
            'Opprettet <a href="%s">post #%s (%s)</a>.',
            action('DommerController@show', $record->id),
            $record->id,
            $record->navn
        );

        return redirect()->action('DommerController@show', $record->id)
            ->with('status', 'Posten ble opprettet.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return response()->view('dommer.show', [
            'record' => DommerRecord::findOrFail($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param DommerSchema $schema
     * @param int $id
     * @return Response
     */
    public function edit(DommerSchema $schema, $id)
    {
        $this->authorize('dommer');

        $record = DommerRecord::findOrFail($id);

        $data = $this->formArguments($record, $schema);

        return response()->view('dommer.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param DommerSchema $schema
     * @param int $id
     * @return Response
     */
    public function update(Request $request, DommerSchema $schema, $id)
    {
        $this->authorize('dommer');
        $record = DommerRecord::findOrFail($id);
        $this->updateOrCreate($request, $schema, $record);

        $this->log(
            'Oppdaterte <a href="%s">post #%s (%s)</a>.',
            action('DommerController@show', $record->id),
            $record->id,
            $record->navn
        );

        return redirect()->action('DommerController@show', $id)
            ->with('status', 'Posten ble lagret');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy($id)
    {
        $this->authorize('dommer');

        //
    }

    /**
     * Autocomplete a field. Currently hard-coded for "kilde", but could easily be expanded if needed.
     *
     * @param Request $request
     * @return Response
     */
    public function autocomplete(Request $request)
    {
        $rows = \DB::table('dommer_kilder')
            ->select('id', 'navn')
            ->get();

        $out = [];
        foreach ($rows as $row) {
            $out[] = [
                'id' => $row->id,
                'label' => $row->navn,
            ];
        }

        return response()->json($out);
    }
}
