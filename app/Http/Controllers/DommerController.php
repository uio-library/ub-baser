<?php

namespace App\Http\Controllers;

use App\DommerRecord;
use Illuminate\Http\Request;

class DommerController extends RecordController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sortColumn = $request->input('sort', 'navn');
        $sortOrder = $request->input('order', 'asc');
        $records = DommerRecord::orderBy($sortColumn, $sortOrder)->paginate(50);
        $data = [
            'columns'    => DommerRecord::$columns,
            'records'    => $records,
            'sortColumn' => $sortColumn,
            'sortOrder'  => $sortOrder,
        ];
        foreach ($data['columns'] as &$d) {
            $d['link'] = Request('url') . '?' . http_build_query([
                'sort'  => $d['field'],
                'order' => ($d['field'] == $sortColumn && $sortOrder == 'asc') ? 'desc' : 'asc',
            ]);
        }

        return response()->view('dommer.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'columns' => DommerRecord::$columns,
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
        //
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
        //
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
