<?php

namespace App\Http\Controllers;

use App\Schema\Schema;
use App\Http\Requests\SearchRequest;
use App\Record;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     * @param Schema $schema
     * @return array
     */
    protected function formArguments(Record $record, Schema $schema)
    {
        $values = [];
        foreach ($schema->keyed() as $key => $field) {
            if ($field->has('column')) {
                $value = $record->{$field->column};
            } elseif ($field->has('modelAttribute')) {
                $value = $record->{$field->modelAttribute};
            } else {
                $value = $record->{$key};
            }
            $values[$key] = old($key, $value);
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
     * @param Schema $schema
     * @param Record $record
     */
    protected function updateRecord(Schema $schema, Record $record, Request $request)
    {
        foreach ($schema->flat() as $field) {
            if (!$field->editable) {
                continue;
            }

            $newValue = $request->get($field->key, $field->defaultValue);

            if (!$field->editable) {
                // Ignore
            } elseif ($field->type == 'persons') {
                // Ignore, these are handled by the specific controller (for now)
            } elseif ($field->has('column')) {
                $record->{$field->getColumn()} = $newValue;
            }
        }

        if (is_null($record->id)) {
            $record->created_by = $request->user()->id;
        }
        $record->updated_by = $request->user()->id;

        $record->save();
    }

    /**
     * Generate JSON response for DataTables.
     *
     * @param SearchRequest $request
     * @param Schema $schema
     * @return JsonResponse
     */
    protected function dataTablesResponse(SearchRequest $request, Schema $schema)
    {
        $fields = $schema->keyed();

        $queryBuilder = $request->queryBuilder;
        $requestedColumns = [];
        $columnReverseMap = [];
        foreach ($request->columns as $k => $v) {
            // Check that only valid column names are requested
            if (!isset($fields[$v['data']])) {
                throw new \RuntimeException('Invalid column name requested: ' . $v['data']);
            }
            $field = $fields[$v['data']];

            $columnReverseMap[$field->getViewColumn()] = $field->key;
            $requestedColumns[$k] = $field->getViewColumn();
        }

        // Always include the id column
        $requestedColumns[] = 'id';
        $columnReverseMap['id'] = 'id';

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
            ->map(function ($row) use ($columnReverseMap) {
                $out = [];
                foreach ($row->toArray() as $k => $v) {
                    if (is_array($v)) {
                        $v = implode(', ', $v);
                    }
                    if (isset($columnReverseMap[$k])) {
                        $out[$columnReverseMap[$k]] = $v;
                    }
                }
                return $out;
            });

        return response()->json([
            'draw' => $request->draw,
            'recordsFiltered' => $recordCount,
            'recordsTotal' => $recordCount,
            'data' => $data,
        ]);
    }
}
