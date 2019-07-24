<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;

class RecordController extends Controller
{
    /**
     * Instantiate a new RecordController instance.
     */
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['create', 'edit', 'store', 'update', 'destroy']]);
    }

    protected function dataTablesResponse(SearchRequest $request, array $keys)
    {
        $queryBuilder = $request->queryBuilder;
        $requestedColumns = [];
        foreach ($request->columns as $k => $v) {
            $requestedColumns[$k] = $v['data'];
        }
        $requestedColumns[] = 'id';

        $queryBuilder->select(array_values($requestedColumns));
        foreach ($request->order as $order) {
            $col = $requestedColumns[$order['column']];
            if (in_array($col, $keys)) {
                $dir = ($order['dir'] == 'asc') ? 'asc' : 'desc';
                $queryBuilder->orderByRaw("$col $dir NULLS LAST");
            }
        }

        $recordCount = (int) $queryBuilder->count();

        $queryBuilder->skip($request->start);
        $queryBuilder->take($request->length);

        $data = $queryBuilder->get()
            ->map(function($row) {
                foreach ($row as $k => $v) {
                    if (is_array($v)) {
                        $row[$k] = implode(', ', $v);
                    }
                }
                return $row;
            });

        return response()->json([
            'draw' => $request->draw,
            'recordsFiltered' => $recordCount,
            'recordsTotal' => $recordCount,
            'data' => $data,
        ]);
    }
}
