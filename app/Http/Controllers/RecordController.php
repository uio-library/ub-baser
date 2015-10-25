<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecordController extends Controller
{
    /**
     * Instantiate a new RecordController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['create', 'edit', 'store', 'update', 'destroy']]);
    }

    public function getIndexData(Request $request, $record)
    {
        $sortColumn = $request->input('sort', 'navn');
        $sortOrder = $request->input('order', 'asc');
        $records = $record::orderBy($sortColumn, $sortOrder)->paginate(50);
        $data = [
            'columns'    => $record::$columns,
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

        return $data;
    }
}
