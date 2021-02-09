<?php

namespace App\Http\Controllers;

use App\Base;
use App\Http\Request;
use App\Http\Requests\DataTableRequest;
use App\Http\Requests\SearchRequest;
use App\Record;
use App\Schema\Schema;
use App\Schema\SchemaField;
use App\Services\AutocompleteServiceInterface;
use App\Services\DataTableProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class BaseController extends Controller
{
    /**
     * The class this controller edits. Defaults to the 'Record' class of the base.
     * @var string
     */
    protected $model;

    /**
     * The class this controller displays. Defaults to $editClass, but can be set to e.g.
     * a view that adds additional, calculcated columns.
     * @var string
     */
    protected $showModel;

    /**
     * Blade view used for the 'show' action.
     *
     * @var string
     */
    protected $showView = 'show';

    /**
     * Blade view used for the 'edit' action.
     *
     * @var string
     */
    protected $editView = 'edit';

    /**
     * Classname of the schema class.
     * @var string
     */
    protected $recordSchema = 'Schema';

    public static $defaultColumns = [];
    public static $defaultSortOrder = [];

    /**
     * Instantiate a new BaseController instance.
     * @param Base|null $base
     */
    public function __construct(Base $base = null)
    {
        $this->middleware('auth', ['only' => ['create', 'edit', 'store', 'update', 'destroy']]);

        if (!is_null($base)) {
            if (is_null($this->model)) {
                $this->model = $base->getClass('Record');
            }
            if (is_null($this->showModel)) {
                $this->showModel = $this->model;
            }
        }

        \View::share('base', $base);
    }

    /**
     * Validation rules when creating or updating a record.
     * @see: https://laravel.com/docs/master/validation
     *
     * @param Record $record
     * @return array
     */
    protected function getValidationRules(Record $record): array
    {
        return [];
    }

    /**
     * Display a listing of records.
     *
     * @param SearchRequest $request
     * @param Base $base
     * @return Response
     */
    public function index(SearchRequest $request, Base $base)
    {
        return response()->view($base->getView('index'), [
            'base' => $base,
            'schema' => $base->getSchema(),
            'settings' => $this->globalSettings($base),

            'query' => $request->all(),
            'processedQuery' => $request->parseQuery(),
            'advancedSearch' => ($request->advanced === 'true'),

            'intro' => $base->getIntro(),

            'defaultColumns' => static::$defaultColumns,

            'order' => $request->getSortOrder(static::$defaultSortOrder),
            'defaultOrder' => static::$defaultSortOrder,
        ]);
    }

    /**
     * Generate JSON response for DataTables.
     *
     * @param DataTableRequest $request
     * @param DataTableProvider $provider
     * @return JsonResponse
     */
    public function data(DataTableRequest $request, DataTableProvider $provider)
    {
        $response = $provider->processRequest($request);

        return response()->json([
            'draw' => $request->draw,
            'recordsFiltered' => $response['count'],
            'recordsTotal' => $response['count'],
            'unknownCount' => $response['unknownCount'],
            'data' => $response['data'],
        ]);
    }

    /**
     * Autocomplete a field.
     *
     * @param Request $request
     * @param Base $base
     * @param AutocompleteServiceInterface $autocompleter
     * @return JsonResponse
     */
    public function autocomplete(Request $request, Base $base, AutocompleteServiceInterface $autocompleter)
    {
        $schema = $base->getSchema();
        $fields = $schema->keyed(true);

        $data = $request->validate([
            'field' => ['required', Rule::in(array_keys($fields))],
            'q' => ['nullable'],
        ]);

        return response()->json([
            'results' => $autocompleter->complete(
                $fields[$data['field']],
                Arr::get($data, 'q')
            ),
        ]);
    }

    /**
     * Show the form for creating a new record.
     *
     * @param Request $request
     * @param Base $base
     * @return Response
     */
    public function create(Request $request, Base $base)
    {
        return response()->view(
            $base->getView('create'),
            $this->formArguments(
                $base->make('Record'),
                $base
            )
        );
    }

    /**
     * Show a record.
     *
     * @param SearchRequest $request
     * @param Base $base
     * @param $id
     * @return Response
     */
    public function show(SearchRequest $request, Base $base, $id)
    {
        $record = $base->getRecord($id, $base->usesSoftDeletes(), $this->showModel);
        if (is_null($record)) {
            abort(404, trans('base.error.recordnotfound'));
        }
        if ($base->usesSoftDeletes() && !\Auth::check() && $record->trashed()) {
            abort(404, trans('base.error.recordtrashed'));
        }

        $schema = $base->getSchema();
        $query = $request->query();
        $query['id'] = $id;

        return response()->view(
            $base->getView($this->showView),
            [
                'title' => $record->getTitle(),
                'base' => $base,
                'schema' => $base->getSchema(),
                'record'  => $record,
                'currentQuery' => $query,
                'order' => $request->getSortOrder(static::$defaultSortOrder),
            ]
        );
    }

    /**
     * Show the form for editing a record.
     *
     * @param Request $request
     * @param Base $base
     * @param $id
     * @return Response
     */
    public function edit(Request $request, Base $base, $id)
    {
        // @TODO: Need to lazy load stuff?? Or can we just add it to the JSON serialization??
        // $record = Record::with('forfattere', 'kritikere')->findOrFail($id);

        $record = $base->getRecord($id, true, $this->model);

        if (is_null($record)) {
            abort(404, trans('base.error.recordnotfound'));
        }

        return response()->view(
            $base->getView($this->editView),
            $this->formArguments($record, $base)
        );
    }

    /**
     * Store a new record and return a redirect response.
     *
     * @param Request $request
     * @param Base $base
     * @throws \Illuminate\Validation\ValidationException
     * @return RedirectResponse
     */
    public function store(Request $request, Base $base)
    {
        $record = $base->newRecord();

        $this->validate($request, $this->getValidationRules($record));

        $this->updateOrCreateRecord($record, $base->getSchema(), $request);

        $url = $base->action('show', $record->id);
        $this->log(
            'Opprettet <a href="%s">post #%s</a>.',
            $url,
            $record->id
        );
        return redirect($url)->with('status', trans('base.notification.recordcreated'));
    }

    /**
     * Update the specified record and return a redirect response.
     *
     * @param Request $request
     * @param Base $base
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, Base $base, $id)
    {
        $record = $base->getRecord($id, true, $this->model) ?? abort(trans('base.error.recordnotfound'));

        $this->validate($request, $this->getValidationRules($record));

        $changes = $this->updateOrCreateRecord($record, $base->getSchema(), $request);

        $url = $base->action('show', $record->id);
        if (count($changes)) {
            $changeList = implode("\n", array_map(
                function ($change) {
                    return "<li>$change</li>";
                },
                $changes
            ));

            $this->log(
                "Oppdaterte post #%s\n<a href=\"%s\">[Lenke]</a><ul>%s</ul>",
                $record->id,
                $url,
                $changeList
            );
        }
        return redirect($url)->with('status', trans('base.notification.recordupdated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Base $base
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(Request $request, Base $base, $id)
    {
        $record = $base->getRecord($id, true, $this->model) ?? abort(trans('base.error.recordnotfound'));

        $record->delete();

        $url = $base->action('show', $record->id);
        $this->log(
            'Slettet <a href="%s">post #%s</a>.',
            $url,
            $record->id
        );
        return redirect($url)->with('status', trans('base.notification.recordtrashed'));
    }

    /**
     * Restore the specified resource from trash.
     *
     * @param Base $base
     * @param int $id
     * @return RedirectResponse
     */
    public function restore(Base $base, $id)
    {
        $record = $base->getRecord($id, true, $this->model) ?? abort(trans('base.error.recordnotfound'));

        $record->restore();

        $url = $base->action('show', $record->id);
        $this->log(
            'Gjenopprettet <a href="%s">post #%s</a>.',
            $url,
            $record->id
        );
        return redirect($url)->with('status', trans('base.notification.recordrecovered'));
    }

    /**
     * Get old value if present (after a validation error) and transform it if necessary.
     *
     * @param SchemaField $field
     * @param string $key
     * @param mixed $default
     * @return mixed string
     */
    protected function old($field, $key, $default)
    {
        if (!is_null(old($key))) {
            $value = old($key);
            if ($field->type === 'persons') {
                $value = json_decode($value, true);
            }
            return $value;
        }

        return $default;
    }

    /**
     * Construct form arguments according to some schema.
     *
     * @param Model $record
     * @param Base $base
     * @return array
     */
    protected function formArguments(Model $record, Base $base): array
    {
        $schema = $base->getSchema($this->recordSchema);
        $values = [];
        foreach ($schema->keyed() as $key => $field) {
            if ($field->has('column')) {
                $value = $record->{$field->column};
            } elseif ($field->has('modelAttribute')) {
                $value = $record->{$field->modelAttribute};
            } else {
                $value = $record->{$key};
            }
            $values[$key] = $this->old($field, $key, $value);
        }

        return [
            'record' => $record,
            'schema' => $schema,
            'values' => $values,
            'base' => $base,
            'settings' => $this->globalSettings($base),
        ];
    }

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
        $changes = [];

        foreach ($schema->flat() as $field) {
            if (!$field->edit->enabled) {
                continue;
            }

            $newValue = $request->get($field->key, $field->defaultValue);

            if ($field->datatype === Schema::DATATYPE_BOOL) {
                $newValue = ($newValue === 'on');
            }
            if ($field->datatype === Schema::DATATYPE_INT) {
                $newValue = intval($newValue);
            }

            if ($field->type == 'entities') {
                // Ignore, these are handled by the specific controller (for now)
            } else {
                if ($record->id) {
                    // Keep a record of changes
                    $oldValueStr = json_encode($record->{$field->getColumn()}, JSON_UNESCAPED_UNICODE);
                    $newValueStr = json_encode($newValue, JSON_UNESCAPED_UNICODE);
                    if ($oldValueStr !== $newValueStr) {
                        if ($oldValueStr === 'null') {
                            $changes[] = "La til '{$field->key}': $newValueStr";
                        } elseif ($newValueStr === 'null') {
                            $changes[] = "Fjernet '{$field->key}': $oldValueStr";
                        } else {
                            $changes[] = "Endret '{$field->key}' fra $oldValueStr til $newValueStr";
                        }
                    }
                }
                $record->{$field->getColumn()} = $newValue;
            }
        }

        if ($record->id === null) {
            $record->created_by = $request->user()->id;
        }
        if ($record->id === null or count($changes) > 0) {
            $record->updated_by = $request->user()->id;
        }

        $record->save();

        $changes = $this->syncEntities($schema, $request, $changes, $record);

        return $changes;
    }

    protected function globalSettings(Base $base)
    {
        return [
            'baseUrl' => $base->action('index'),
        ];
    }

    public function redirectToRecord(Base $base, $numeric)
    {
        return redirect($base->action('show', $numeric));
    }

    public function redirectToHome(Base $base)
    {
        return redirect($base->action('index'));
    }

    public function prev(Base $base, SearchRequest $request)
    {
        $schema = $base->getSchema();
        $idField = $schema->primaryId;
        $id = $request->getPreviousRecord(static::$defaultSortOrder, $request->id);

        $model = $base->getClass('RecordView')::find($id);

        $query = $request->query();
        $query['id'] = $model->{$idField};

        return redirect($base->action('show', $query));
    }

    public function next(Base $base, SearchRequest $request)
    {
        $schema = $base->getSchema();
        $idField = $schema->primaryId;
        $id = $request->getNextRecord(static::$defaultSortOrder, $request->id);

        $model = $base->getClass('RecordView')::find($id);

        $query = $request->query();
        $query['id'] = $model->{$idField};

        return redirect($base->action('show', $query));
    }

    /**
     * @param Schema $schema
     * @param Request $request
     * @param array $changes
     * @param Model $record
     * @return array
     */
    protected function syncEntities(Schema $schema, Request $request, array $changes, Model $record): array
    {
        foreach ($schema->flat() as $field) {
            $newValue = $request->get($field->key, $field->defaultValue);

            if ($field->type == 'entities') {
                $values = json_decode($newValue, true);
                if ($field->entityRelation == EntitiesField::MANY_TO_MANY_RELATION) {
                    $changes = array_merge($changes, $this->syncManyToManyRelation($record, $field, $values));
                } else {
                    $changes = array_merge($changes, $this->syncOneToManyRelation($record, $field, $values));
                }
            }
        }
        return $changes;
    }

    /**
     * Sync many-to-many-relation for a field.
     *
     * @param Model $record
     * @param EntitiesField $field
     * @param array $input
     *
     * @return array
     */
    protected function syncOneToManyRelation(Model $record, EntitiesField $field, array $input): array
    {
        $attribute = $field->modelAttribute;
        $entityModel = $field->entityType;
        $relatedPivotKey = $field->relatedPivotKey;
        $changes = [];
        $encountered = [];

        // Add
        $entities = [];
        foreach ($input as $inputValue) {
            $entityId = (int) $inputValue['id'];
            $entity = $entityModel::findOrFail($entityId);
            $encountered[] = $entityId;
            if ($entity->{$relatedPivotKey} != $record->id) {
                $changes[] = 'La til: ' . json_encode($entity, JSON_UNESCAPED_UNICODE);
                $entity->{$relatedPivotKey} = $record->id;
                $entity->save();
            }
        }

        // Remove
        foreach ($record->{$attribute} as $entity) {
            $changes[] = 'Fjernet: ' . json_encode($entity, JSON_UNESCAPED_UNICODE);
            if (!in_array($entity->id, $encountered)) {
                $entity->delete();
            }
        }

        return $changes;
    }

    /**
     * Sync many-to-many-relation for a field.
     *
     * @param Model $record
     * @param EntitiesField $field
     * @param array $input
     *
     * @return array
     */
    protected function syncManyToManyRelation(Model $record, EntitiesField $field, array $input): array
    {
        $attribute = $field->modelAttribute;
        $pivotTable = $field->pivotTable;
        $pivotTableKey = $field->pivotTableKey;
        $relatedPivotKey = $field->relatedPivotKey;
        $entityModel = $field->entityType;

        $pivotFields = array_map(
            function ($field) {
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
                $relatedPivotKey => $record->id,
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
                $relatedPivotKey => $record->id,
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
            $changes[] = 'Fjernet: ' . json_encode($entity, JSON_UNESCAPED_UNICODE);
            \DB::table($pivotTable)
                ->where([
                    $relatedPivotKey => $entity[$relatedPivotKey],
                    $pivotTableKey => $entity[$pivotTableKey],
                ])
                ->delete();
        }

        // Insert new ones
        foreach ($insert as $entity) {
            $changes[] = 'La til: ' . json_encode($entity, JSON_UNESCAPED_UNICODE);
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
}
