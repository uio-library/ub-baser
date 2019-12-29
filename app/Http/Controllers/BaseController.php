<?php

namespace App\Http\Controllers;

use App\Base;
use App\Http\Request;
use App\Http\Requests\SearchRequest;
use App\Record;
use App\Schema\Schema;
use App\Schema\SchemaField;
use App\Services\AutocompleteServiceInterface;
use App\Services\DataTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class BaseController extends Controller
{
    static public $defaultColumns = [];
    static public $defaultSortOrder = [];

    /**
     * Instantiate a new BaseController instance.
     */
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['create', 'edit', 'store', 'update', 'destroy']]);
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
            'processedQuery' => $request->getQueryParts(),
            'advancedSearch' => ($request->advanced === 'on'),

            'intro' => $base->getIntro(),

            'defaultColumns' => static::$defaultColumns,
            'order' => static::$defaultSortOrder,
        ]);
    }

    /**
     * Generate JSON response for DataTables.
     *
     * @param SearchRequest $request
     * @param Schema $schema
     * @return JsonResponse
     */
    public function data(SearchRequest $request, Schema $schema)
    {
        $dataTable = app(DataTable::class);
        $response = $dataTable->formatResponse($request, $schema);

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
        $fields = $schema->keyed();

        $data = $request->validate([
            'field' => ['required', Rule::in(array_keys($fields))],
            'q' => ['nullable'],
        ]);

        return response()->json(
            $autocompleter->complete(
                $fields[$data['field']],
                Arr::get($data, 'q')
            )
        );
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
     * @param Request $request
     * @param Base $base
     * @param $id
     * @return Response
     */
    public function show(Request $request, Base $base, $id)
    {
        $record = $base->getRecord($id);
        if (is_null($record)) {
            abort(404, trans('base.error.recordnotfound'));
        }
        if (!\Auth::check() && $record->trashed()) {
            abort(404, trans('base.error.recordtrashed'));
        }

        return response()->view(
            $base->getView('show'),
            [
                'title' => $record->getTitle(),
                'base' => $base,
                'schema' => $base->getSchema(),
                'record'  => $record,
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

        $record = $base->getRecord($id);
        if (is_null($record)) {
            abort(404, trans('base.error.recordnotfound'));
        }

        return response()->view(
            $base->getView('edit'),
            $this->formArguments($record, $base)
        );
    }

    /**
     * Store a new record and return a redirect response.
     *
     * @param Request $request
     * @param Base $base
     * @return RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
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
        $record = $base->getRecord($id) ?? abort(trans('base.error.recordnotfound'));

        $this->validate($request, $this->getValidationRules($record));

        $changes = $this->updateOrCreateRecord($record, $base->getSchema(), $request);

        $url = $base->action('show', $record->id);
        if (count($changes)) {
            $this->log(
                'Oppdaterte <a href="%s">post #%s</a>.',
                $url,
                $record->id
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
        $record = $base->getRecord($id) ?? abort(trans('base.error.recordnotfound'));

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
        $record = $base->getRecord($id) ?? abort(trans('base.error.recordnotfound'));

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
        if (old($key) !== null) {
            $value = old($key);
            if ($field->type === 'persons') {
                $value = json_decode($value, true);
            }
        }

        return $default;
    }

    /**
     * Construct form arguments according to some schema.
     *
     * @param Record $record
     * @param Base $base
     * @return array
     */
    protected function formArguments(Record $record, Base $base): array
    {
        $schema = $base->getSchema();
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
            if (!$field->editable) {
                continue;
            }

            $newValue = $request->get($field->key, $field->defaultValue);

            if ($field->datatype === Schema::DATATYPE_BOOL) {
                $newValue = ($newValue === 'on');
            }
            if ($field->datatype === Schema::DATATYPE_INT) {
                $newValue = intval($newValue);
            }

            if ($field->type == 'persons') {
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

        return $changes;
    }

    protected function globalSettings(Base $base)
    {
        return [
            'baseUrl' => $base->action('index'),
        ];
    }

}
