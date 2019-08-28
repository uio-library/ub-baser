<?php

namespace App\Http\Controllers;

use App\BaseSchema;
use App\Http\Requests\SearchRequest;
use App\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RecordController extends Controller
{
    /**
     * Instantiate a new RecordController instance.
     */
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['create', 'edit', 'store', 'update', 'destroy']]);
    }

    /**
     * Construct form arguments according to some schema.
     *
     * @param Record $record
     * @param BaseSchema $schema
     * @return array
     */
    protected function formArguments(Record $record, BaseSchema $schema)
    {
        $values = [];
        foreach ($schema->keyed() as $key => $col) {
            if (Arr::has($col, 'edit.column')) {
                $values[$key] = $record->{$col['edit']['column']};
            } elseif (Arr::has($col, 'model_attribute')) {
                $values[$key] = $record->{$col['model_attribute']};
            } else {
                $values[$key] = old($key, $record->{$key});
            }
        }

        return [
            'record' => $record,
            'schema' => $schema,
            'values' => $values,
        ];
    }

    /**
     * Store a newly created record, or update an existing one.
     *
     * @param Request $request
     * @param BaseSchema $schema
     * @param Record $record
     */
    protected function updateRecord(BaseSchema $schema, Record $record, Request $request)
    {
        foreach ($schema->flat() as $field) {
            if (Arr::get($field, 'edit') === false) {
                continue;
            }
            $datatype = Arr::get($field, 'type', 'simple');

            $newValue = $request->get(
                $field['key'],
                Arr::get($field, 'default')
            );

            if (in_array($datatype, ['simple', 'autocomplete', 'url', 'boolean', 'tags'])) {
                $record->{$field['key']} = $newValue;
            } elseif ($datatype == 'select') {
                $record->{$field['edit']['column']} = $newValue;
            } elseif ($datatype == 'persons') {
                // Ignore, these are handled by the specific controller
            } elseif ($datatype == 'incrementing') {
                // Ignore
            } else {
                throw new \RuntimeException("Unsupported datatype: $datatype");
            }
        }

        $record->updated_by = $request->user()->id;
        if (is_null($record->id)) {
            $record->created_by = $request->user()->id;
        }

        $record->save();
    }

    /**
     * Generate JSON response for DataTables.
     */
    protected function dataTablesResponse(SearchRequest $request, BaseSchema $schema)
    {
        $validColumnNames = array_keys($schema->keyed());

        $queryBuilder = $request->queryBuilder;
        $requestedColumns = [];
        foreach ($request->columns as $k => $v) {

            // Check that only valid column names are requested
            if (!in_array($v['data'], $validColumnNames, true)) {
                throw new \RuntimeException('Invalid column name requested: ' . $v['data']);
            }
            $requestedColumns[$k] = $v['data'];
        }
        $requestedColumns[] = 'id';

        $queryBuilder->select(array_values($requestedColumns));
        foreach ($request->order as $order) {

            // Check that only valid column names are requested
            if (!isset($requestedColumns[(int) $order['column']])) {
                throw new \RuntimeException('Invalid order by requested: ' . $order['column']);
            }
            $col = $requestedColumns[(int) $order['column']];
            $dir = ($order['dir'] == 'asc') ? 'asc' : 'desc';

            $queryBuilder->orderByRaw("$col $dir NULLS LAST");
        }

        $recordCount = (int) $queryBuilder->count();

        $queryBuilder->skip($request->start);
        $queryBuilder->take($request->length);

        $data = $queryBuilder->get()
            ->map(function ($row) {
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
