<?php

namespace App\Http\Controllers;

use App\Http\Requests\LetrasSearchRequest;
use App\LetrasRecord;
use App\Page;
use App\RecordQBuilderLetras;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class LetrasController extends RecordController
{
    /**
     * Display a listing of the resource.
     *
     * @param LetrasSearchRequest $request
     * @return \Illuminate\Http\Response
     */

    public function index(LetrasSearchRequest $request)
    {
        if ($request->wantsJson()) {
            return $this->dataTablesResponse($request, LetrasRecord::getKeys());
        }

        $introPage = Page::where('name', '=', 'letras.intro')->first();
        $intro = $introPage ? $introPage->body : '';

        return response()->view('letras.index', [
            'schema' => LetrasRecord::getSchema(),

            'query' => $request->all(),
            'processedQuery' => $request->queryParts,
            'advancedSearch' => ($request->advanced === 'on'),

            'intro' => $intro,

            'defaultColumns' => [
                // Verket
                'forfatter',
                'tittel',
                'utgivelsesaar',

                // Oversettelsen
                'oversetter',
                'tittel2',
                'utgivelsesaar2',
            ],
            'order' => [
                ['key' => 'utgivelsesaar', 'direction' => 'desc'],
            ]

        ]);
    }

    public function autocomplete(Request $request)
    {
        $fieldName = $request->get('field');
        $columns = LetrasRecord::getSchemaByKey();
        if (!isset($columns[$fieldName])) {
            throw new \RuntimeException('Invalid field');
        }
        $fieldDef = $columns[$fieldName];

        if (is_null($fieldDef)) {
            throw new \RuntimeException('Invalid autocomplete field');
        }

        $term = $request->get('q') . '%';
        $data = [];

        switch ($fieldName) {

            default:
                if ($term == '%') {
                    // Preload request
                    $rows = \DB::table('letras')
                        ->select($fieldName)
                        ->distinct()
                        ->groupBy($fieldName)
                        ->get();
                } else {
                    $rows = \DB::table('letras')
                        ->select($fieldName)
                        ->distinct()
                        ->where($fieldName, 'ilike', $term)
                        ->groupBy($fieldName)
                        ->get();
                }
                foreach ($rows as $row) {
                    $data[] = [
                        'value' => $row->{$fieldName},
                    ];
                }
                break;
        }

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function updateOrCreate(Request $request, $id = null)
    {
        $record = is_null($id) ? new LetrasRecord() : LetrasRecord::findOrFail($id);

        $this->validate($request, [
//            'forfatter'     => 'required' . (is_null($id) ? '' : ',' . $id) . '|max:255',
//            'land'      => 'required',
//            'tittel'     => 'required',
//            'utgivelsesaar' => 'required',
//            'sjanger' => 'required',
//            'oversetter' => 'required',
//            'tittel2' => 'required',
//            'utgivelsessted' => 'required',
//            'utgivelsesaar2' => 'required',
//            'forlag' => 'required',
//            'foretterord' => 'required',
//            'spraak' => 'required',
        ]);

        $record->forfatter = $request->get('forfatter');
        $record->land = $request->get('land');
        $record->tittel = $request->get('tittel');
        $record->utgivelsesaar = $request->get('utgivelsesaar');
        $record->sjanger = $request->get('sjanger');
        $record->oversetter = $request->get('oversetter');
        $record->tittel2 = $request->get('tittel2');
        $record->utgivelsessted = $request->get('utgivelsessted');
        $record->utgivelsesaar2 = $request->get('utgivelsesaar2');
        $record->forlag = $request->get('forlag');
        $record->foretterord = $request->get('foretterord');
        $record->spraak = $request->get('spraak');


        $record->save();

        return $record;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('letras');

        $schema = LetrasRecord::getSchema();

        $values = [];
        foreach (LetrasRecord::getSchemaByKey() as $key => $col) {
            $values[$key] = old($key);
        }

        return response()->view('letras.create', [
            'schema' => LetrasRecord::getSchema(),
            'values' => $values,
        ]);
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
        $this->authorize('letras');

        $record = $this->updateOrCreate($request);

        return redirect()->action('LetrasController@show', $record->id)
            ->with('status', 'Posten ble opprettet.');
    }

    public function show($id)
    {
        $record = LetrasRecord::findOrFail($id);

        $data = [
            'schema' => LetrasRecord::getSchema(),
            'record'  => $record,
        ];

        return response()->view('letras.show', $data);
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
        $this->authorize('letras');

        $record = LetrasRecord::findOrFail($id);

        $schema = LetrasRecord::getSchema();

        $values = [];
        foreach (LetrasRecord::getSchemaByKey() as $key => $col) {
            $values[$key] = old($key, $record->{$key});
        }

        return response()->view('letras.edit', [
            'record' => $record,
            'schema' => $schema,
            'values' => $values,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('letras');

        $this->updateOrCreate($request, $id);

        return redirect()->action('LetrasController@show', $id)
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
        $this->authorize('letras');

        //
    }
}
