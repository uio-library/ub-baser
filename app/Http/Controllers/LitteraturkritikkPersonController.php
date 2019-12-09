<?php

namespace App\Http\Controllers;

use App\Litteraturkritikk\Person;
use Illuminate\Http\Request;

class LitteraturkritikkPersonController extends RecordController
{
    protected $logGroup = 'norsk-litteraturkritikk';

    /**
     * @return array List of genders.
     */
    public static function getGenderList()
    {
        return [
            'f'  => 'Kvinne',
            'm'  => 'Mann',
            'u'  => 'Ukjent',
            // etc...
        ];
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
        \Debugbar::startMeasure('query', 'DB query');
        $data = [
            'person' => Person::withTrashed()->with(
                'recordsAsAuthor',
                'recordsAsAuthor.forfattere',
                'recordsAsAuthor.kritikere',
                'recordsAsCritic',
                'recordsAsCritic.forfattere',
                'recordsAsCritic.kritikere'
            )->findOrFail($id),
        ];
        \Debugbar::stopMeasure('query');

        \Debugbar::startMeasure('render', 'Time for rendering');
        $view = response()->view('litteraturkritikk.persons.show', $data);
        \Debugbar::stopMeasure('render');

        return $view;
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

        $data = [
            'person' => Person::findOrFail($id),
            'kjonnliste' => self::getGenderList(),
        ];

        return response()->view('litteraturkritikk.persons.edit', $data);
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

        $person = Person::findOrFail($id);

        $maxYear = date('Y') + 1;

        $this->validate($request, [

            // Lastname is required, firstname is optional
            'etternavn'    => 'required',

            // We don't allow dates in the future, but we don't enforce a minimum limit.
            // Even negative values (B.C.) should be allowed.
            'fodt'   => "nullable|numeric|max:{$maxYear}",
            'dod'   => "nullable|numeric|max:{$maxYear}",
        ]);

        $person->etternavn = $request->get('etternavn');
        $person->fornavn = $request->get('fornavn');
        $person->fodt = $request->get('fodt');
        $person->dod = $request->get('dod');
        $person->kjonn = $request->get('kjonn');
        $person->bibsys_id = $request->get('bibsys_id');
        $person->wikidata_id = $request->get('wikidata_id');

        $person->save();

        $this->log(
            'Oppdaterte <a href="%s">person #%s (%s)</a>.',
            action('LitteraturkritikkPersonController@show', $person->id),
            $person->id,
            "{$person->etternavn}, {$person->fornavn}"
        );

        \DB::unprepared('REFRESH MATERIALIZED VIEW litteraturkritikk_records_search');

        return redirect()->action('LitteraturkritikkPersonController@show', $id)
            ->with('status', 'Personen ble lagret');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->authorize('litteraturkritikk');

        if (!$request->get('confirm-deleteperson')) {
            return redirect()->back()
                ->with('status', 'Manglet bekreftelse');
        }

        $this->log(
            'Slettet <a href="%s">person #%s (%s)</a>.',
            action('LitteraturkritikkPersonController@show', $person->id),
            $person->id,
            "{$person->etternavn}, {$person->fornavn}"
        );

        $person = Person::findOrFail($id);
        $person->records()->detach();

        $person->delete();

        \DB::unprepared('REFRESH MATERIALIZED VIEW litteraturkritikk_records_search');

        return redirect()->action('LitteraturkritikkPersonController@show', $id)
            ->with('status', 'Personen ble slettet');
    }
}
