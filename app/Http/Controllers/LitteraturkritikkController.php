<?php

namespace App\Http\Controllers;

use App\Http\Requests\LitteraturkritikkSearchRequest;
use App\Litteraturkritikk\Autocompleter;
use App\Litteraturkritikk\LitteraturkritikkSchema;
use App\Litteraturkritikk\Person;
use App\Litteraturkritikk\Record;
use App\Page;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
     * @param LitteraturkritikkSchema        $schema
     *
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
            ],
        ]);
    }

    public function autocomplete(Request $request, LitteraturkritikkSchema $schema, Autocompleter $autocompleter)
    {
        $fieldName = $request->get('field');
        if (!isset($schema->{$fieldName})) {
            throw ValidationException::withMessages([
                'field' => ['Invalid field'],
            ]);
        }

        return response()->json(
            $autocompleter->complete(
                $schema,
                $schema->{$fieldName},
                $request->get('q')
            )
        );
    }

    /**
     * Store a newly created record, or update an existing one.
     *
     * @param \Illuminate\Http\Request $request
     * @param LitteraturkritikkSchema  $schema
     * @param Record                   $record
     *
     * @throws ValidationException
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
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
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
     * @param LitteraturkritikkSchema  $schema
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
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
     * @param int                     $id
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
     * @param LitteraturkritikkSchema  $schema
     * @param int                      $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\Response
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

        $this->log(
            'Slettet <a href="%s">post #%s</a>.',
            action('LitteraturkritikkController@show', $record->id),
            $record->id
        );

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

        $this->log(
            'Gjenopprettet <a href="%s">post #%s</a>.',
            action('LitteraturkritikkController@show', $record->id),
            $record->id
        );

        // Refresh view
        \DB::unprepared('REFRESH MATERIALIZED VIEW CONCURRENTLY litteraturkritikk_records_search');

        return redirect()->action('LitteraturkritikkController@show', $id)
            ->with('status', 'Posten ble gjenopprettet');
    }

    /**
     * Sync persons for a record.
     * (The most messy method in UB-baser?).
     *
     * @param Record $record
     * @param array  $input
     *
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
