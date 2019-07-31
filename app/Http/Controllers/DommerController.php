<?php

namespace App\Http\Controllers;

use App\Dommer\DommerKilde;
use App\Dommer\DommerRecord;
use App\Dommer\DommerRecordView;
use App\Dommer\DommerSchema;
use App\Http\Requests\DommerSearchRequest;
use App\Page;
use Illuminate\Http\Request;

class DommerController extends RecordController
{
    protected function getKilder()
    {
        $kilder = [];
        foreach (DommerKilde::all() as $kilde) {
            $kilder[$kilde->id] = $kilde->navn;
        }

        return $kilder;
    }

    /**
     * Display a listing of the resource.
     *
     * @param DommerSearchRequest $request
     * @param DommerSchema $schema
     * @return \Illuminate\Http\Response
     */
    public function index(DommerSearchRequest $request, DommerSchema $schema)
    {
        if ($request->wantsJson()) {
            return $this->dataTablesResponse($request, $schema);
        }

        $introPage = Page::where('slug', '=', 'dommer/intro')->first();
        $intro = $introPage ? $introPage->body : '';

        return response()->view('dommer.index', [
            'schema' => $schema->get(),

            'query' => $request->all(),
            'processedQuery' => $request->queryParts,
            'advancedSearch' => ($request->advanced === 'on'),

            'intro' => $intro,

            'defaultColumns' => [
                'navn',
                'kilde_navn',
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
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return DommerRecord
     */
    protected function updateOrCreate(Request $request, $id = null)
    {
        $record = is_null($id) ? new DommerRecord() : DommerRecord::findOrFail($id);

        $this->validate($request, [
            'navn'     => 'required|unique:dommer,navn' . (is_null($id) ? '' : ',' . $id) . '|max:255',
            'aar'      => 'required|digits:4',
            'side'     => 'required|numeric|min:1|max:9999',
            'kilde_id' => 'required|numeric',
        ]);

        $record->navn = $request->get('navn');
        $record->aar = $request->get('aar');
        $record->side = $request->get('side');
        $record->kilde_id = $request->get('kilde_id');

        $record->save();

        return $record;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('dommer');

        $data = [
            'columns' => config('baser.dommer.columns'),
            'kilder'  => $this->getKilder(),
        ];

        return response()->view('dommer.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('dommer');

        $record = $this->updateOrCreate($request);

        return redirect()->action('DommerController@show', $record->id)
            ->with('status', 'Posten ble opprettet.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = [
            'record' => DommerRecord::findOrFail($id),
        ];

        return response()->view('dommer.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param DommerSchema $schema
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(DommerSchema $schema, $id)
    {
        $this->authorize('dommer');

        $record = DommerRecord::findOrFail($id);

        $values = [];
        foreach ($schema->keyed() as $key => $col) {
            $value = $record->{$key};
            if ($key == 'kilde') {
                $value = [
                    'id' => $value->id,
                    'label' => $value->navn,
                ];
            }
            $values[$key] = old($key, $value);

        }

        return response()->view('dommer.edit', [
            'record' => $record,
            'schema' => $schema,
            'values' => $values,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('dommer');

        $this->updateOrCreate($request, $id);

        return redirect()->action('DommerController@show', $id)
            ->with('status', 'Posten ble lagret');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('dommer');

        //
    }

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
