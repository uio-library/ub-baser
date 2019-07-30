<?php

namespace App\Http\Controllers;

use App\Http\Requests\LitteraturkritikkSearchRequest;
use App\Litteraturkritikk\KritikkType;
use App\Litteraturkritikk\LitteraturkritikkSchema;
use App\Litteraturkritikk\Person;
use App\Litteraturkritikk\PersonView;
use App\Litteraturkritikk\Record;
use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class LitteraturkritikkController extends RecordController
{
    /**
     * @return array List of genders.
     */
    public static function getGenderList()
    {
        return [
            'f'  => 'Kvinne',
            'm'  => 'Mann',
            'u'  => 'Ukjent',
            // etc...
        ];
    }

    /**
     * Display a listing of the resource.
     *
     *
     * @param LitteraturkritikkSearchRequest $request
     * @param LitteraturkritikkSchema $schema
     * @return \Illuminate\Http\Response
     */
    public function index(LitteraturkritikkSearchRequest $request, LitteraturkritikkSchema $schema)
    {
        if ($request->wantsJson()) {
            return $this->dataTablesResponse($request, $schema);
        }

        $introPage = Page::where('name', '=', 'litteraturkritikk.intro')->first();
        $intro = $introPage ? $introPage->body : '';

        return response()->view('litteraturkritikk.index', [
            'schema' => $schema,

            'query' => $request->all(),
            'processedQuery' => $request->queryParts,
            'advancedSearch' => ($request->advanced === 'on'),

            'intro' => $intro,

            'defaultColumns' => [
                // Verket
                'verk_tittel',
                'verk_forfatter',
                'verk_dato',

                // Kritikken
                'kritiker',
                'publikasjon',
                'dato',
            ],
            'order' => [
                ['key' => 'verk_dato', 'direction' => 'desc'],
            ]
        ]);
    }

    public function autocomplete(Request $request, LitteraturkritikkSchema $schema)
    {
        $fieldName = $request->get('field');
        $fields = $schema->keyed();
        if (!isset($fields[$fieldName])) {
            throw new \RuntimeException('Invalid field');
        }
        $fieldDef = $fields[$fieldName];

        $term = $request->get('q') . '%';
        $data = [];

        switch ($fieldName) {

            case 'publikasjon':
            case 'spraak':
            case 'verk_spraak':
            case 'verk_sjanger':
            case 'verk_tittel':
            case 'utgivelsessted':
            case 'verk_utgivelsessted':
                if ($term == '%') {
                    // Preload request
                    $rows = \DB::table('letras')
                        ->select($fieldName)
                        ->distinct()
                        ->groupBy($fieldName)
                        ->get();

                    foreach ($rows as $row) {
                        $data[] = [
                            'value' => $row->{$fieldName},
                        ];
                    }
                } else {

                    if (in_array($fieldName, ['verk_tittel'])) {
                        $query = \DB::table('litteraturkritikk_records_search')
                            ->whereRaw("verk_tittel_ts @@ (phraseto_tsquery('simple', ?)::text || ':*')::tsquery", [$term]);
                    } else {
                        $query = \DB::table('litteraturkritikk_records_search')
                            ->where($fieldName, 'ilike', $term);
                    }

                    foreach ($query->groupBy($fieldName)
                                 ->select([$fieldName, \DB::raw('COUNT(*) AS cnt')])
                                 ->orderBy('cnt', 'desc')
                                 ->limit(25)
                                 ->get() as $res) {
                        $data[] = [
                            'value' => $res->{$fieldName},
                            'count' => $res->cnt,
                        ];
                    }
                }
                break;

            case 'person':
            case 'verk_forfatter':
            case 'kritiker':
                $personRolle = Arr::get($fieldDef, 'person_role');
                $query = PersonView::select(
                    'id', 'etternavn_fornavn', 'etternavn', 'fornavn', 'kjonn',
                    'fodt', 'dod', 'bibsys_id', 'wikidata_id'
                )
                    ->whereRaw(
                        "any_field_ts @@ (phraseto_tsquery('simple', ?)::text || ':*')::tsquery",
                        [$term]
                    );
                if ($personRolle) {
                    $query->whereRaw('? = ANY(roller)', [$personRolle]);
                } else {
                    // Skjul personer som ikke er i bruk
                    $query->whereRaw('CARDINALITY(roller) != 0');
                }

                foreach ($query->limit(25)->get() as $res) {
                    $data[] = [
                        'id' => $res->id,
                        'value' => $res->etternavn_fornavn,
                        'record' => $res,
                    ];
                }
                break;

            case 'kritikktype':
                foreach (KritikkType::where('navn', 'ilike', $term)->select('navn')->get() as $res) {
                    $data[] = [
                        'value' => $res->navn
                    ];
                }
                break;

            default:
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
    protected function updateOrCreate(Request $request, LitteraturkritikkSchema $schema, $id = null)
    {
        $isNew = is_null($id);
        $record = $isNew ? new Record() : Record::findOrFail($id);

        $this->validate($request, [
            'kritikktype' => 'required',
            'dato' => [
                'nullable',
                'regex:/^(?:u\.å\.|n\.d\.|[0-9]{4}(-[0-9]{2})?(-[0-9]{2})?)$/',
            ],
        ]);

        $persons = [];

        foreach ($schema->flat() as $col) {
            if (Arr::get($col, 'edit') === false) {
                continue;
            }
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

    protected function formArguments(Record $record, LitteraturkritikkSchema $schema)
    {

        $values = [];
        foreach ($schema->keyed() as $key => $col) {
            if (Arr::has($col, 'model_attribute')) {
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
     * Show the form for creating a new resource.
     *
     * @param LitteraturkritikkSchema $schema
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(LitteraturkritikkSchema $schema)
    {
        $this->authorize('litteraturkritikk');

        $data = $this->formArguments(new Record(), $schema);

        return response()->view('litteraturkritikk.create', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(LitteraturkritikkSchema $schema, $id)
    {
        $this->authorize('litteraturkritikk');

        $record = Record::with('forfattere', 'kritikere')->findOrFail($id);
        $data = $this->formArguments($record, $schema);

        return response()->view('litteraturkritikk.edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @param LitteraturkritikkSchema $schema
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, LitteraturkritikkSchema $schema)
    {
        $this->authorize('litteraturkritikk');

        $record = $this->updateOrCreate($request, $schema);

        return redirect()->action('LitteraturkritikkController@show', $record->id)
            ->with('status', 'Posten ble opprettet.');
    }

    /**
     * Display the specified resource.
     *
     * @param LitteraturkritikkSchema $schema
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(LitteraturkritikkSchema $schema, $id)
    {
        $record = Record::findOrFail($id);

        $data = [
            'title' => $record->tittel ?: '#' . $record->id,
            'schema' => $schema,
            'record' => $record,
        ];

        return response()->view('litteraturkritikk.show', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param LitteraturkritikkSchema $schema
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, LitteraturkritikkSchema $schema, $id)
    {
        $this->authorize('litteraturkritikk');

        $this->updateOrCreate($request, $schema, $id);

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
