<?php

namespace App\Http\Controllers;

use App\Http\Requests\LitteraturkritikkSearchRequest;
use App\Litteraturkritikk\LitteraturkritikkSchema;
use App\Litteraturkritikk\Person;
use App\Litteraturkritikk\PersonView;
use App\Litteraturkritikk\Record;
use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class LitteraturkritikkController extends RecordController
{
    protected $logGroup = 'norsk-litteraturkritikk';

    public function redir()
    {
        return redirect()->action('LitteraturkritikkController@index');
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

        $introPage = Page::where('slug', '=', 'norsk-litteraturkritikk/intro')->first();
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
                ['key' => 'dato', 'direction' => 'asc'],
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
                            ->whereRaw(
                                "verk_tittel_ts @@ (phraseto_tsquery('simple', ?)::text || ':*')::tsquery",
                                [$term]
                            );
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
                    'id',
                    'etternavn_fornavn',
                    'etternavn',
                    'fornavn',
                    'kjonn',
                    'fodt',
                    'dod',
                    'bibsys_id',
                    'wikidata_id'
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
            case 'tags':
                # Ref: https://stackoverflow.com/a/31757242/489916
                # for the #>> '{}' magick
                $results = \DB::select("
                    select
                      distinct jd.value #>> '{}' as value
                    from
                      litteraturkritikk_records,
                      jsonb_array_elements(litteraturkritikk_records.${fieldName}) as jd
                    order by value
                ");
                foreach ($results as $row) {
                    $data[] = [
                        'value' => $row->value,
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
     * @param LitteraturkritikkSchema $schema
     * @param Record $record
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function updateOrCreate(Request $request, LitteraturkritikkSchema $schema, Record $record) : array
    {
        // Validate input
        $this->validate($request, [
        ]);

        // Update record
        $changes = $this->updateRecord($schema, $record, $request);

        // Sync persons
        $persons = [];
        foreach ($schema->flat() as $field) {
            $newValue = $request->get($field->key, $field->defaultValue);

            if ($field->type == 'persons') {
                foreach (json_decode($newValue, true) as $input) {
                    $persons[] = $input;
                }
            }
        }
        $changes = array_merge($changes, $this->syncPersons($record, $persons));

        // Refresh view
        \DB::unprepared('REFRESH MATERIALIZED VIEW CONCURRENTLY litteraturkritikk_records_search');

        return $changes;
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

        $record = new Record();
        $this->updateOrCreate($request, $schema, $record);

        $this->log(
            'Opprettet <a href="%s">post #%s (%s)</a>.',
            action('LitteraturkritikkController@show', $record->id),
            $record->id,
            $record->tittel
        );

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
        if (\Auth::check()) {
            $record = Record::withTrashed()->findOrFail($id);
        } else {
            $record = Record::findOrFail($id);
        }

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
        $record = Record::findOrFail($id);
        $changes = $this->updateOrCreate($request, $schema, $record);

        if (count($changes) != 0) {
            $this->log(
                "Oppdaterte post #%s\n%s\n<a href=\"%s\">[Post]</a>",
                $record->id,
                implode("\n", $changes),
                action('LitteraturkritikkController@show', $record->id)
            );
        }
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
        $record = Record::findOrFail($id);

        $record->delete();

        // Refresh view
        \DB::unprepared('REFRESH MATERIALIZED VIEW CONCURRENTLY litteraturkritikk_records_search');

        return redirect()->action('LitteraturkritikkController@show', $id);
    }

    /**
     * Restore the specified resource from trash.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $record = Record::withTrashed()->findOrFail($id);

        $record->restore();

        // Refresh view
        \DB::unprepared('REFRESH MATERIALIZED VIEW CONCURRENTLY litteraturkritikk_records_search');

        return redirect()->action('LitteraturkritikkController@show', $id)
            ->with('status', 'Posten ble gjenopprettet');
    }

    /**
     * Sync persons for a record.
     * (The most messy method in UB-baser?)
     *
     * @param Record $record
     * @param array $input
     * @return array
     */
    protected function syncPersons(Record $record, array $input): array
    {
        // Apologies.
        // Initially, the plan was to use the Eloquent sync() method here, but it doesn't
        // support attaching the same model multiple times with different pivot data, so
        // it can't be used to attach the same author multiple times with different roles.
        // Therefore we have to get our hands a bit dirty here.

        $changes = [];
        $names = [];

        // Make a list of current persons
        $currentPersons = [];
        foreach ($record->persons as $person) {
            $names[$person->id] = (string) $person;
            $currentPersons[] = [
                'record_id' => $record->id,
                'person_id' => $person->id,
                'person_role' => $person->pivot->person_role,
                'kommentar' => $person->pivot->kommentar,
                'pseudonym' => $person->pivot->pseudonym,
                'posisjon' => $person->pivot->posisjon,
            ];
        }

        // Make a list of new persons
        $persons = [];
        foreach ($input as $inputValue) {
            $person = $this->findOrCreatePerson($inputValue);
            $names[$person->id] = (string) $person;
            $persons[] = [
                'record_id' => $record->id,
                'person_id' => $person->id,
                'person_role' => $inputValue['pivot']['person_role'],
                'kommentar' => $inputValue['pivot']['kommentar'],
                'pseudonym' => $inputValue['pivot']['pseudonym'],
                'posisjon' => $inputValue['pivot']['posisjon'],
            ];
        }

        // Compare the two to figure out which updates we need to do on
        // the record-person pivot table.
        $delete = array_udiff($currentPersons, $persons, function ($a, $b) {
            return strcmp(json_encode($a), json_encode($b));
        });
        $insert = array_udiff($persons, $currentPersons, function ($a, $b) {
            return strcmp(json_encode($a), json_encode($b));
        });

        // Delete relations that no longer exist
        foreach ($delete as $recordPerson) {
            \DB::table('litteraturkritikk_record_person')
                ->where($recordPerson)
                ->delete();
            $changes[] = "Fjernet {$names[$recordPerson['person_id']]} (Rolle: {$recordPerson['person_role']}, " .
                "pseudonym: {$recordPerson['pseudonym']}, kommentar: {$recordPerson['kommentar']}, posisjon: {$recordPerson['posisjon']})";
        }

        // Insert new ones
        foreach ($insert as $recordPerson) {
            \DB::table('litteraturkritikk_record_person')
                ->insert($recordPerson);
            $changes[] = "La til {$names[$recordPerson['person_id']]} (Rolle: {$recordPerson['person_role']}, " .
                "pseudonym: {$recordPerson['pseudonym']}, kommentar: {$recordPerson['kommentar']}, posisjon: {$recordPerson['posisjon']})";
        }

        return $changes;
    }

    protected function findOrCreatePerson(array $input)
    {
        if (isset($input['id'])) {
            // It *should* exist. If not, the input is invalid and it's ok to throw an error.
            return Person::findOrFail($input['id']);
        }

        $person = Person::create([
            'etternavn' => $input['etternavn'],
            'fornavn' => $input['fornavn'],
            'kjonn' => $input['kjonn'],
        ]);

        $this->log(
            'Opprettet <a href="%s">person #%s (%s)</a>.',
            action('LitteraturkritikkPersonController@show', $person->id),
            $person->id,
            "{$person->etternavn}, {$person->fornavn}"
        );

        return $person;
    }
}
