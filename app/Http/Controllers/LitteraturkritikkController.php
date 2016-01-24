<?php

namespace App\Http\Controllers;

use App\Litteraturkritikk\KritikkType;
use App\Litteraturkritikk\Person;
use App\Litteraturkritikk\PersonView;
use App\Litteraturkritikk\Record;
use App\Litteraturkritikk\RecordView;
use App\Page;
use Illuminate\Http\Request;
use Punic\Language;

class LitteraturkritikkController extends RecordController
{
    public function getKritikkTyper()
    {
        $kritikktyper = [];
        foreach (KritikkType::all() as $kilde) {
            $kritikktyper[$kilde->navn] = $kilde->navn;
        }

        return $kritikktyper;
    }

    public function getLanguageList()
    {
        return Language::getAll(true, true, 'nb');
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

        // Create an instances of \Illuminate\Database\Eloquent\Builder
        $records = RecordView::query();

        if ($dateRange[0] != $minYear || $dateRange[1] != $maxYear) {
            $records->where('aar_numeric', '>=', intval($dateRange[0]))
                ->where('aar_numeric', '<=', intval($dateRange[1]));
        }

        if (array_has($fields, 'q')) {
            $term = $fields['q'];
            $records->whereRaw("document @@ plainto_tsquery('simple', '" . pg_escape_string($term) . "')");
        }

        if (array_has($fields, 'person')) {
            $records->whereIn('id', function($query) use ($fields) {
                $query
                    ->select('litteraturkritikk_record_person.record_id')
                    ->from('litteraturkritikk_personer_view AS litteraturkritikk_personer')
                    ->join('litteraturkritikk_record_person', 'litteraturkritikk_record_person.person_id', '=', 'litteraturkritikk_personer.id')
                    ->where('etternavn_fornavn', '=', $fields['person'])
                    ->orWhere('fornavn_etternavn', '=', $fields['person'])
                ;
            });
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

        if (array_has($fields, 'sjanger')) {
            $q = $fields['sjanger'];
            $records->where('sjanger', '=', $q);
        }

        $selectOptions = [
            ['id' => 'q', 'type' => 'text', 'label' => 'Fritekst', 'placeholder' => 'Forfatter, kritiker, ord i tittel, kommentar, etc...', 'options' => []],
            ['id' => 'person', 'type' => 'select', 'label' => 'Forfatter eller kritiker', 'placeholder' => 'Fornavn og/eller etternavn', 'options' => []],
            ['id' => 'verk', 'type' => 'text', 'label' => 'Omtalt tittel', 'placeholder' => 'Verkstittel', 'options' => []],
            ['id' => 'publikasjon', 'type' => 'select', 'label' => 'Publikasjon', 'placeholder' => 'Publikasjon', 'options' => []],
            ['id' => 'verk_sjanger', 'type' => 'select', 'label' => 'Verk-sjanger', 'placeholder' => 'F.eks. lyrikk, roman, ...', 'options' => ['Kake', 'Bake']],
            ['id' => 'kritikktype', 'type' => 'select', 'label' => 'Kritikktype', 'placeholder' => 'F.eks. teaterkritikk, forfatterportrett, ...', 'options' => []],
        ];

        // Make sure there's always at least one input field visible
        if (!count($fieldPairs)) {
            $fieldPairs[] = ['q', ''];
        }

        $intro = Page::where('name', '=', 'litteraturkritikk.intro')->first();

        $records
            ->with('forfattere', 'kritikere')
            ->orderBy('aar_numeric', 'desc');

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
        if ($field == 'publikasjon') {
            if ($term != '%') {
                // Ignore preload request
                foreach (RecordView::where($field, 'ilike', $term)->limit(25)->select($field)->get() as $res) {
                    $data[] = [
                        'value' => $res[$field]
                    ];
                }
            }
        } elseif ($field == 'verk_sjanger') {
            if ($term == '%') {
                // Preload request
                $rows = \DB::select('select verk_sjanger from litteraturkritikk_records group by verk_sjanger');
                foreach ($rows as $row) {
                    $data[] = [
                        'value' => $row->verk_sjanger
                    ];
                }
            } else {
                foreach (RecordView::where($field, 'ilike', $term)->groupBy($field)->limit(25)->select($field)->get() as $res) {
                    $data[] = [
                        'value' => $res[$field]
                    ];
                }
            }
        } elseif ($field == 'kritikktype') {
            foreach (KritikkType::where('navn', 'ilike', $term)->limit(25)->select('navn')->get() as $res) {
                $data[] = [
                    'value' => $res->navn
                ];
            }
        } elseif ($field == 'person') {
            foreach (PersonView::where('etternavn_fornavn', 'ilike', $term)
                     ->orWhere('fornavn_etternavn', 'ilike', $term)
                     ->limit(25)->select('id', 'etternavn_fornavn', 'bibsys_id', 'birth_year')->get() as $res) {
                $data[] = [
                    'id' => $res->id,
                    'bibsys_id' => $res->bibsys_id,
                    'birth_year' => $res->birth_year,
                    'value' => $res->etternavn_fornavn,
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
     * @param int                      $id
     *
     * @return Record
     */
    protected function updateOrCreate(Request $request, $id = null)
    {
        $isNew = is_null($id);
        $record = $isNew ? new Record() : Record::findOrFail($id);

        $this->validate($request, [
            'kritikktype'    => 'required',
            'kommentar'      => '',
            'spraak'         => 'required|in:' . implode(',', array_keys($this->getLanguageList())),
            'tittel'         => 'max:255',
            'publikasjon'    => '',
        ]);

        $record->kritikktype = $request->get('kritikktype');
        $record->spraak = $request->get('spraak');
        $record->tittel = $request->get('tittel');
        $record->publikasjon = $request->get('publikasjon');
        $record->utgivelsessted = $request->get('utgivelsessted');
        $record->aar = $request->get('aar');

        $record->dato = $request->get('dato');
        $record->aargang = $request->get('aargang');
        $record->bind = $request->get('bind');
        $record->hefte = $request->get('hefte');
        $record->nummer = $request->get('nummer');
        $record->sidetall = $request->get('sidetall');

        $record->kommentar = $request->get('kommentar');
        $record->utgivelseskommentar = $request->get('utgivelseskommentar');

        $record->verk_tittel = $request->get('verk_tittel');
        $record->verk_aar = $request->get('verk_aar');
        $record->verk_sjanger = $request->get('verk_sjanger');
        $record->verk_spraak = $request->get('verk_spraak');
        $record->verk_kommentar = $request->get('verk_kommentar');
        $record->verk_utgivelsessted = $request->get('verk_utgivelsessted');

        $record->forfatter_mfl = $request->get('forfatter_mfl') ? true : false;
        $record->kritiker_mfl = $request->get('kritiker_mfl') ? true : false;

        $record->updated_by = $request->user()->id;
        if ($isNew) {
            $record->created_by = $request->user()->id;
        }

        $record->save();

        $is_edited = $request->get('is_edited');

        $persons = [];
        foreach ( $this->parsePersons($request->get('forfattere', [])) as $id) {
            $persons[$id] = ['person_role' => $is_edited ? 'redaktør' : 'forfatter'];
        }
        foreach ( $this->parsePersons($request->get('kritikere', [])) as $id) {
            $persons[$id] = ['person_role' => 'kritiker'];
        }

        $record->persons()->sync($persons);

        \DB::unprepared('REFRESH MATERIALIZED VIEW litteraturkritikk_records_search');

        return $record;
    }

    protected function isEdited($record)
    {
        foreach ($record->forfattere as $person) {
            if ($person->person_role == 'redaktør') {
                return true;
            }
        }

        return false;
    }

    protected function formArguments($record)
    {
        return [
            'columns'       => config('baser.beyer.columns'),
            'kritikktyper'  => $this->getkritikktyper(),
            'kjonnstyper'   => $this->getKjonnstyper(),
            'spraakliste'   => $this->getLanguageList(),
            'record'        => $record,
            'is_edited'     => $this->isEdited($record),
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
     * @param $names
     * @return array
     */
    protected function parsePersons($names)
    {
        $ids = [];
        foreach ($names as $name) {
            preg_match('/^(.+?)(, ([^(]+))?( \((.+?)\-\))?$/', $name, $matches);

            $args = [
                'etternavn' => $matches[1],
                'fornavn' => array_get($matches, 3, null),
                'birth_year' => array_get($matches, 5, null),
            ];

            $person = Person::firstOrCreate($args);

            $ids[] = $person->id;
        }
        return $ids;
    }
}
