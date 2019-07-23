<?php

namespace App\Http\Controllers;

use App\Http\Requests\LitteraturkritikkSearchRequest;
use App\Litteraturkritikk\KritikkType;
use App\Litteraturkritikk\Person;
use App\Litteraturkritikk\PersonView;
use App\Litteraturkritikk\Record;
use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class LitteraturkritikkController extends RecordController
{
    /**
     * Display a listing of the resource.
     *
     *
     * @param LitteraturkritikkSearchRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(LitteraturkritikkSearchRequest $request)
    {
        $queryBuilder = $request->queryBuilder;

        $allFields = Record::getColumns();
        $allFieldKeys = [];
        foreach ($allFields as $group) {
            foreach ($group['fields'] as $field) {
                $allFieldKeys[] = $field['key'];
            }
        }

        if ($request->wantsJson()) {

            // Parse request parameters from DataTables
            $requestedColumns = [];
            foreach ($request->columns as $k => $v) {
                $requestedColumns[$k] = $v['data'];
            }
            $requestedColumns[] = 'id';

            $queryBuilder->select(array_values($requestedColumns));
            foreach ($request->order as $order) {
                $col = $requestedColumns[$order['column']];
                if (in_array($col, $allFieldKeys)) {
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

        } else {

            $intro = Page::where('name', '=', 'litteraturkritikk.intro')->first();

            // Make sure there's always at least one input field visible
            if (!count($request->queryParts)) {
                $request->queryParts[] = ['field' => 'q', 'operator' => 'eq', 'value' => ''];
            }

            $qs = $request->all();
            $qsJson = json_encode($qs);
            $qs = http_build_query($qs);
            $viewQs = strlen($qs) ? "?{$qs}&" : "?";

            $data = [
                'intro' => $intro->body,
                'query' => $request->queryParts,
                'allFields' => $allFields,
                'searchFields' => Record::getSearchFields(),
                'advancedSearch' => ($request->advanced === 'on'),
                'qs' => $viewQs,
                'qsJson' => $qsJson,
            ];

            return response()->view('litteraturkritikk.index', $data);
        }
    }

    public function autocomplete(Request $request)
    {
        $field = $request->get('field');
        $term = $request->get('q') . '%';
        $data = [];
        if (in_array($field, ['publikasjon', 'spraak', 'verk_spraak', 'verk_sjanger', 'verk_tittel'])) {
            if ($term == '%') {
                // Preload request
                $rows = \DB::select('select verk_sjanger from litteraturkritikk_records group by verk_sjanger');
                foreach ($rows as $row) {
                    $data[] = [
                        'value' => $row->verk_sjanger
                    ];
                }
            } else {

                if (in_array($field, ['verk_tittel'])) {
                    $query = \DB::table('litteraturkritikk_records_search')
                        ->whereRaw("verk_tittel_ts @@ (phraseto_tsquery('simple', ?)::text || ':*')::tsquery", [$term]);
                } else {
                    $query = \DB::table('litteraturkritikk_records_search')
                        ->where($field, 'ilike', $term);
                }

                foreach ($query->groupBy($field)
                             ->select([$field, \DB::raw('COUNT(*) AS cnt')])
                             ->orderBy('cnt', 'desc')
                             ->limit(25)
                             ->get() as $res) {
                    $data[] = [
                        'value' => $res->{$field},
                        'count' => $res->cnt,
                    ];
                }
            }
        } elseif ($field == 'kritikktype') {
            foreach (KritikkType::where('navn', 'ilike', $term)->select('navn')->get() as $res) {
                $data[] = [
                    'value' => $res->navn
                ];
            }
        } elseif (in_array($field, ['person', 'forfatter', 'kritiker'])) {
            $query = PersonView::select(
                    'id', 'etternavn_fornavn', 'etternavn', 'fornavn', 'kjonn',
                    'fodt', 'dod', 'bibsys_id', 'wikidata_id'
                )
                ->whereRaw(
                    "any_field_ts @@ (phraseto_tsquery('simple', ?)::text || ':*')::tsquery",
                    [$term]
                );
            if ($field != 'person') {
                $query->whereRaw('? = ANY(roller)', [$field]);
            }

            foreach ($query->limit(25)->get() as $res) {
                $data[] = [
                    'id' => $res->id,
                    'value' => $res->etternavn_fornavn,
                    'record' => $res,
                ];
            }
        } else {
            throw new \ErrorException('Unknown search field');
        }

        return response()->json($data);
    }

    /**
     * Store a newly created record, or update an existing one.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return Record
     */
    protected function updateOrCreate(Request $request, $id = null)
    {
        $isNew = is_null($id);
        $record = $isNew ? new Record() : Record::findOrFail($id);

        $this->validate($request, [
            // 'kritikktype' => 'required',
        ]);

        $persons = [];

        foreach (Record::getColumnsFlatList() as $col) {
            $datatype = Arr::get($col, 'type', 'simple');
            $newValue = $request->get($col['key'], Arr::get($col, 'default'));
            if (in_array($datatype, ['simple', 'autocomplete', 'url', 'boolean', 'tags'])) {
                $record->{$col['key']} = $newValue;
            } elseif ($datatype == 'persons') {
                foreach (json_decode($newValue, true) as $input) {
                    $persons[] = $input;
                }
            } elseif ($datatype == 'incrementing') {
                // Ignore
            } else {
                throw new \RuntimeException("Unsupported datatype: $datatype");
            }
        }

        $record->updated_by = $request->user()->id;
        if ($isNew) {
            $record->created_by = $request->user()->id;
        }

        $record->save();

        // Sync persons
        $personsSyncData = $this->findOrCreatePersons($persons);
        $record->persons()->sync($personsSyncData);

        \DB::unprepared('REFRESH MATERIALIZED VIEW litteraturkritikk_records_search');

        return $record;
    }

    /**
     * Get the first non-standard person role. While the database schema
     * supports setting role per person, the UI currently only supports
     * setting it per record. So we threat it as a per-record property for
     * now. If needed, we can support role per-person in the future.
     */
    protected function getPersonRole($record)
    {
        foreach ($record->forfattere as $person) {
            if ($person->pivot->person_role != 'forfatter') {
                return $person->pivot->person_role;
            }
        }

        return '';
    }

    protected function formArguments($record)
    {
        $columns = Record::getColumns();
        $columnsFlat = Record::getColumnsFlatList();
        $values = [];
        foreach ($columnsFlat as $col) {
            $key = $col['key'];
            if (Arr::get($col, 'type') == 'persons') {
                $values[$key] = $record->{$col['model_attribute']};
            } else {
                $values[$key] = old($key, $record->{$key});
            }
        }
        return [
            'columns' => $columns,
            'values' => $values,
            'record' => $record,
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('litteraturkritikk');

        $data = $this->formArguments(new Record());

        return response()->view('litteraturkritikk.create', $data);
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
        $this->authorize('litteraturkritikk');

        $record = Record::with('forfattere', 'kritikere')->findOrFail($id);
        $data = $this->formArguments($record);

        return response()->view('litteraturkritikk.edit', $data);
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
        $this->authorize('litteraturkritikk');

        $record = $this->updateOrCreate($request);

        return redirect()->action('LitteraturkritikkController@show', $record->id)
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
        $record = Record::findOrFail($id);

        $data = [
            'columns' => Record::getColumns(),
            'record' => $record,
        ];

        return response()->view('litteraturkritikk.show', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('litteraturkritikk');

        $this->updateOrCreate($request, $id);

        return redirect()->action('LitteraturkritikkController@show', $id)
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
        //
    }

    /**
     * Looks up IDs of persons from names, creates new person when not found.
     * @param array $inputs
     * @return array
     */
    protected function findOrCreatePersons(array $inputs): array
    {
        $idsWithPivotData = [];
        foreach ($inputs as $input) {
            if (isset($input['id'])) {
                // Validate existence
                $person = Person::findOrFail($input['id']);
            } else {
                $person = Person::firstOrCreate($input);
            }
            $idsWithPivotData[$person->id] = $input['pivot'];
        }
        return $idsWithPivotData;
    }
}
