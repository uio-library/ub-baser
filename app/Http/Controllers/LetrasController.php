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
    public function create()
    {
        //
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
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    /**
    public function show($id)
    {
        
        $record = LetrasRecord::findOrFail($id);
 

        $data = [
            'columns' => config('baser.letras.columns'),
            'record' => LetrasRecord::findOrFail($id),
        ];

        return response()->view('letras.show', $data);
    }*/

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
        //
    }
}
