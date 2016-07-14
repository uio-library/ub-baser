<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OpesController extends RecordController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    {
        list($fields, $fieldPairs) = $this->parseFields($request);

        $minYear = '1789';
        $maxYear = strftime('%Y');
        $dateRange = [$minYear, $maxYear];

        /*$inputDateRange = preg_split('/[-:,]/', $request->get('date', ''));
        if (count($inputDateRange) == 2 && strlen($inputDateRange[0]) == 4 && strlen($inputDateRange[1]) == 4) {
            $dateRange = $inputDateRange;
        }
        */
        $records = OpesRecordView::query();

        /*if ($dateRange[0] != $minYear || $dateRange[1] != $maxYear) {
            $records->where('aar_numeric', '>=', intval($dateRange[0]))
                ->where('aar_numeric', '<=', intval($dateRange[1]));
        }*/

        if (array_has($fields, 'q')) {
            $term = $fields['q']; 
            echo = "test";
            $records->whereRaw("tsv @@ plainto_tsquery('simple', '" . pg_escape_string($term) . "')");
        }

        /* if (array_has($fields, 'person')) {
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
        */


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

        $records->orderBy('aar_numeric', 'desc');

        $data = [
            'intro'         => $intro->body,
            'records'       => $records->paginate(200),
            'fields'        => $fieldPairs,
            'selectOptions' => $selectOptions,
            'date'          => $dateRange,
            'minDate'       => $minYear,
            'maxDate'       => $maxYear,
        ];

        return response()->view('opes.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //
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
        //
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
        //
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
        //
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
