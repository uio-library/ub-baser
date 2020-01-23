<?php

namespace App\Bases\Litteraturkritikk;

use App\Http\Controllers\BaseController;
use App\Http\Request;
use App\Record;
use App\Schema\Schema;

class Controller extends BaseController
{
    protected $logGroup = 'norsk-litteraturkritikk';

    public static $defaultColumns = [
        // Verket
        'verk_tittel',
        'verk_forfatter',
        'verk_dato',

        // Kritikken
        'kritiker',
        'publikasjon',
        'dato',
    ];

    public static $defaultSortOrder = [
        ['key' => 'dato', 'direction' => 'asc'],
    ];

    /**
     * Store a newly created record, or update an existing one.
     *
     * @param Record  $record
     * @param Schema  $schema
     * @param Request $request
     * @return array
     */
    protected function updateOrCreateRecord(Record $record, Schema $schema, Request $request): array
    {
        // Update the main record
        $changes = parent::updateOrCreateRecord($record, $schema, $request);

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
            action('\App\Bases\Litteraturkritikk\PersonController@show', $person->id),
            $person->id,
            "{$person->etternavn}, {$person->fornavn}"
        );

        return $person;
    }
}
