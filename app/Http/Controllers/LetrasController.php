<?php

namespace App\Http\Controllers;

use App\LetrasRecord;
use App\Page;
use App\RecordQBuilderLetras;
use Illuminate\Http\Request;

class LetrasController extends RecordController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        
        $q = new RecordQBuilderLetras($request, 'letras', LetrasRecord::class);
        $q->make();

        $data = [
            'prefix' => 'letras',
            'query' => $request->all(),
            'columns' => $q->getColumns(),
            'sortColumn' => $q->sortColumn,
            'sortOrder' => $q->sortOrder,
        ];

        $data['records'] = $q->query
            ->paginate(50);

        return response()->view('letras.index', $data); 
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function updateOrCreate(Request $request, $id = null)
    {
        $record = is_null($id) ? new LetrasRecord() : LetrasRecord::findOrFail($id);

        $this->validate($request, [
            'forfatter'     => 'required' . (is_null($id) ? '' : ',' . $id) . '|max:255',
            'land'      => 'required',
            'tittel'     => 'required',
            'utgivelsesaar' => 'required',
            'sjanger' => 'required',
            'oversetter' => 'required',
            'tittel2' => 'required',
            'utgivelsessted' => 'required',
            'utgivelsesaar2' => 'required',
            'forlag' => 'required',
            'foretterord' => 'required',
            'spraak' => 'required',
        ]);

        $record->forfatter = $request->get('forfatter');
        $record->land = $request->get('land');
        $record->tittel = $request->get('tittel');
        $record->utgivelsesaar = $request->get('utgivelsesaar');
        $record->sjanger = $request->get('sjanger');
        $record->oversetter = $request->get('oversetter');
        $record->tittel2 = $request->get('tittel2');
        $record->utgivelsessted = $request->get('utgivelsessted');
        $record->utgivelsesaar2 = $request->get('utgivelsesaar2');
        $record->forlag = $request->get('forlag');
        $record->foretterord = $request->get('foretterord');
        $record->spraak = $request->get('spraak');
        
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
        $this->authorize('letras');

        $data = [
            'columns' => config('baser.letras.columns'),
        ];

        return response()->view('letras.create', $data);
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
        $this->authorize('letras');

        $record = $this->updateOrCreate($request);

        return redirect()->action('LetrasController@show', $record->id)
            ->with('status', 'Posten ble opprettet.');
    }


public function show($id)
    {
    $record = LetrasRecord::findOrFail($id);
     
     $data = [
            'columns' => config('baser.letras.columns'),
            'record'  => $record,
        ];

        return response()->view('letras.show', $data);
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
        $this->authorize('letras');

        $record = LetrasRecord::findOrFail($id);

        $data = [
            'record'   => $record,
        ];

        return response()->view('letras.edit', $data);
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
        $this->authorize('letras');

        $this->updateOrCreate($request, $id);

        return redirect()->action('LetrasController@show', $id)
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
        $this->authorize('letras');

        //
    }
}
