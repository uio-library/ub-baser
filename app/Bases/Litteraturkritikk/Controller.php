<?php

namespace App\Bases\Litteraturkritikk;

use App\Base;
use App\Exceptions\NationalLibraryRecordNotFound;
use App\Http\Controllers\BaseController;
use App\Http\Request;
use App\Record;
use App\Schema\EntitiesField;
use App\Schema\Schema;
use App\Services\NationalLibraryApi;
use Illuminate\Validation\ValidationException;

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
        try {
            $changes = parent::updateOrCreateRecord($record, $schema, $request);
        } catch (NationalLibraryRecordNotFound $ex) {
            throw ValidationException::withMessages([
                $ex->field => ['Klarte ikke å slå opp URL-en: ' . $ex->url],
            ]);
        }

        // Sync entities
        foreach ($schema->flat() as $field) {
            $newValue = $request->get($field->key, $field->defaultValue);

            if ($field->type == 'entities') {
                $values = json_decode($newValue, true);
                $changes = array_merge($changes, $this->syncEntities($record, $field, $values));
            }
        }

        return $changes;
    }

    /**
     * Sync entities for a record.
     *
     * @param Record $record
     * @param EntitiesField $field
     * @param array $input
     *
     * @return array
     */
    protected function syncEntities(Record $record, EntitiesField $field, array $input): array
    {
        $attribute = $field->modelAttribute;
        $pivotTable = $field->pivotTable;
        $pivotTableKey = $field->pivotTableKey;
        $entityModel = $field->entityType;

        $pivotFields = array_map(
            function($field) {
                return $field->shortKey;
            },
            $field->pivotFields
        );

        // Initially, the plan was to use the Eloquent sync() method here, but it doesn't
        // support attaching the same model multiple times with different pivot data, so
        // it can't be used to attach the same author multiple times with different roles.
        // Therefore we have to get our hands a bit dirty here.

        $changes = [];
        $names = [];

        // Make a list of current entities
        $currentEntities = [];
        foreach ($record->{$attribute} as $entity) {
            $names[$entity->id] = (string) $entity;
            $value = [
                'record_id' => $record->id,
                $pivotTableKey => $entity->id,
            ];
            foreach ($pivotFields as $pf) {
                $value[$pf] = $entity->pivot->{$pf};
            }
            $currentEntities[] = $value;
        }

        // Make a list of new entities
        $entities = [];
        foreach ($input as $inputValue) {
            $entity = $entityModel::findOrFail($inputValue['id']);
            $names[$entity->id] = (string) $entity;
            $value = [
                'record_id' => $record->id,
                $pivotTableKey => $entity->id,
            ];
            foreach ($pivotFields as $pf) {
                $value[$pf] = ($inputValue['pivot'][$pf] === '') ? null : $inputValue['pivot'][$pf];
            }
            $entities[] = $value;
        }

        // Compare the two to figure out which updates we need to do on
        // the record-entity pivot table.
        $delete = array_udiff($currentEntities, $entities, function ($a, $b) {
            return strcmp(json_encode($a), json_encode($b));
        });
        $insert = array_udiff($entities, $currentEntities, function ($a, $b) {
            return strcmp(json_encode($a), json_encode($b));
        });

        // Delete relations that no longer exist
        foreach ($delete as $entity) {
            $changes[] = "Fjernet: " . json_encode($entity, JSON_UNESCAPED_UNICODE);
            \DB::table($pivotTable)
                ->where([
                    'record_id' => $entity['record_id'],
                    $pivotTableKey => $entity[$pivotTableKey],
                ])
                ->delete();
        }

        // Insert new ones
        foreach ($insert as $entity) {
            $changes[] = "La til: " . json_encode($entity, JSON_UNESCAPED_UNICODE);
            foreach ($entity as $k => $v) {
                if (is_array($v)) {
                    $entity[$k] = json_encode($v);
                }
            }
            \DB::table($pivotTable)
                ->insert($entity);
        }

        return $changes;
    }

    protected function nationalLibrarySearch(
        Request $request,
        NationalLibraryApi $api
    ) {
        // Note: Because of the repeated filter= statements, we need to get the unprocessed query string
        $query = $request->server->get('QUERY_STRING');

        return response()->json(
            $api->request($query)
        );
    }

    protected function listIndex(Base $base, Request $request, AggregateLists $lists)
    {
        return view('litteraturkritikk.lists.index', [
            'lists' => $lists->all(),
        ]);
    }

    protected function listShow(Base $base, Request $request, AggregateLists $lists, $id)
    {
        $list = $lists->get($id);

        if (is_null($list)) {
            abort(404, 'List not found');
        }

        $sort = $request->get('sort', 'record_count');

        return view('litteraturkritikk.lists.show', [
            'list' => $list,
            'sort' => $sort,
            'aggs' => $list->getResults($sort),
        ]);
    }
}
