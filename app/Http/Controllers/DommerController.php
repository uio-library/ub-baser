<?php

namespace App\Http\Controllers;

use App\DommerKilde;
use App\DommerRecord;
use App\Page;
use App\RecordQueryBuilder;
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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $q = new RecordQueryBuilder($request, 'dommer', DommerRecord::class);
        $q->make();

        $data = [
            'prefix' => 'dommer',
            'query' => $request->all(),
            'columns' => $q->getColumns(),
            'sortColumn' => $q->sortColumn,
            'sortOrder' => $q->sortOrder,
            'intro' => Page::where('name', '=', 'dommer.intro')->first()->body,
        ];

        $data['records'] = $q->query
            ->join('dommer_kilder', 'dommer.kilde_id', '=', 'dommer_kilder.id')
            ->select('dommer.*', 'dommer_kilder.navn AS kilde_navn')
            ->paginate(50);

        $data['kilder'] = $this->getKilder();

        return response()->view('dommer.index', $data);
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
            'kilde_id' => 'required',
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
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('dommer');

        $record = DommerRecord::findOrFail($id);

        $data = [
            'record'   => $record,
            'kilder'   => $this->getKilder(),
        ];

        return response()->view('dommer.edit', $data);
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
}
