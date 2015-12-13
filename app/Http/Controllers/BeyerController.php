<?php

namespace App\Http\Controllers;

use App\BeyerKritikkType;
use App\BeyerRecord;
use App\BeyerRecordView;
use App\Page;
use Illuminate\Http\Request;
use Punic\Language;

class BeyerController extends RecordController
{
    public function getKritikkTyper()
    {
        $kritikktyper = [];
        foreach (BeyerKritikkType::all() as $kilde) {
            $kritikktyper[$kilde->navn] = $kilde->navn;
        }

        return $kritikktyper;
    }

    public function getLanguageList()
    {
        return Language::getAll(true, true, 'nb');
    }

    public function getKjonnstyper()
    {
        return [
            'f'  => 'Kvinne',
            'm'  => 'Mann',
            'u'  => 'Ukjent',
            // etc...
        ];
    }

    /**
     * Turns ['input1field' => 'A', 'input1value' => 'B', 'input2field' => 'C', 'input2value' => 'D', ...]
     * into ['A' => 'B', 'C' => 'D', ...] and [['A', 'B'], ['C', 'D']].
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function parseFields(Request $request)
    {
        $fields = [];
        $fieldPairs = [];
        foreach ($request->all() as $key => $value) {
            if (preg_match('/^input([0-9]+)value$/', $key, $matches)) {
                $keyField = 'input' . $matches[1] . 'field';
                $field = array_get($request, $keyField);
                if ($field && $value) {
                    $fields[$field] = $value;
                    $fieldPairs[] = [$field, $value];
                }
            }
        }

        return [$fields, $fieldPairs];
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        list($fields, $fieldPairs) = $this->parseFields($request);

        $minYear = '1789';
        $maxYear = strftime('%Y');
        $dateRange = [$minYear, $maxYear];

        $inputDateRange = preg_split('/[-:,]/', $request->get('date', ''));
        if (count($inputDateRange) == 2 && strlen($inputDateRange[0]) == 4 && strlen($inputDateRange[1]) == 4) {
            $dateRange = $inputDateRange;
        }

        $records = BeyerRecordView::query();

        if ($dateRange[0] != $minYear || $dateRange[1] != $maxYear) {
            $records->where('aar_numeric', '>=', intval($dateRange[0]))
                ->where('aar_numeric', '<=', intval($dateRange[1]));
        }

        if (array_has($fields, 'q')) {
            $term = $fields['q'];
            $records->whereRaw("tsv @@ plainto_tsquery('simple', '" . pg_escape_string($term) . "')");
        }

        if (array_has($fields, 'person')) {
            $term = preg_replace('/,/', '', $fields['person']) . '%';
            $records->whereRaw("tsv_person @@ plainto_tsquery('simple', '" . pg_escape_string($term) . "')");
        }

        if (array_has($fields, 'kritikktype')) {
            $q = $fields['kritikktype'];
            // Note: The ~@ operator is defined in <2015_12_13_120034_add_extra_operators.php>
            $records->whereRaw('kritikktype ~@ \'' . pg_escape_string($q) . '\'');
        }

        if (array_has($fields, 'verk')) {
            $q = $fields['verk'] . '%';
            $records->where('verk_tittel', 'ilike', $q);
        }

        if (array_has($fields, 'publikasjon')) {
            $q = $fields['publikasjon'];
            $records->where('publikasjon', '=', $q);
        }

        $selectOptions = [
            ['id' => 'q', 'type' => 'text', 'label' => 'Fritekst', 'placeholder' => 'Forfatter, kritiker, ord i tittel, kommentar, etc...'],
            ['id' => 'person', 'type' => 'text', 'label' => 'Forfatter eller kritiker', 'placeholder' => 'Fornavn og/eller etternavn'],
            ['id' => 'verk', 'type' => 'text', 'label' => 'Omtalt tittel', 'placeholder' => 'Verkstittel'],
            ['id' => 'publikasjon', 'type' => 'select', 'label' => 'Publikasjon', 'placeholder' => 'Publikasjon'],
            ['id' => 'kritikktype', 'type' => 'select', 'label' => 'Kritikktype', 'placeholder' => 'F.eks. teaterkritikk, forfatterportrett, ...'],
        ];

        // Make sure there's always at least one input field visible
        if (!count($fieldPairs)) {
            $fieldPairs[] = ['q', ''];
        }

        $intro = Page::where('name', '=', 'litteraturkritikk.intro')->first();

        $data = [
            'intro'         => $intro->body,
            'records'       => $records->paginate(200),
            'fields'        => $fieldPairs,
            'selectOptions' => $selectOptions,
            'date'          => $dateRange,
            'minDate'       => $minYear,
            'maxDate'       => $maxYear,
        ];

        return response()->view('litteraturkritikk.index', $data);
    }

    public function search(Request $request)
    {
        $field = $request->get('field');
        $term = $request->get('q') . '%';
        $data = [];
        if (in_array($field, ['publikasjon'])) {
            foreach (BeyerRecordView::where($field, 'ilike', $term)->limit(25)->select($field)->get() as $res) {
                $data[] = ['value' => $res[$field]];
            }
        } elseif ($field == 'kritikktype') {
            foreach (BeyerKritikkType::where('navn', 'ilike', $term)->limit(25)->select('navn')->get() as $res) {
                $data[] = ['value' => $res->navn];
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
     * @param int                      $id
     *
     * @return BeyerRecord
     */
    protected function updateOrCreate(Request $request, $id = null)
    {
        $record = is_null($id) ? new BeyerRecord() : BeyerRecord::findOrFail($id);

        $this->validate($request, [
            'kritikktype'    => 'required',
            'kommentar'      => '',
            'spraak'         => 'required|in:' . implode(',', array_keys($this->getLanguageList())),
            'tittel'         => 'max:255',
            'publikasjon'    => '',
        ]);

        $record->kritikktype = $request->get('kritikktype');
        $record->kommentar = $request->get('kommentar');
        $record->spraak = $request->get('spraak');
        $record->tittel = $request->get('tittel');
        $record->publikasjon = $request->get('publikasjon');

        $record->save();

        return $record;
    }

    protected function formArguments($record)
    {
        return [
            'columns'       => config('baser.beyer.columns'),
            'kritikktyper'  => $this->getkritikktyper(),
            'kjonnstyper'   => $this->getKjonnstyper(),
            'spraakliste'   => $this->getLanguageList(),
            'record'        => $record,
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

        $data = $this->formArguments(new BeyerRecord());

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

        $record = BeyerRecord::findOrFail($id);
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

        return redirect()->action('BeyerController@show', $record->id)
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
        $record = BeyerRecord::findOrFail($id);

        $data = [
            'columns' => config('baser.beyer.columns'),
            'record'  => $record,
        ];

        return response()->view('litteraturkritikk.show', $data);
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
        $this->authorize('litteraturkritikk');

        $this->updateOrCreate($request, $id);

        return redirect()->action('BeyerController@show', $id)
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
}
