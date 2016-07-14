<?php

namespace App\Http\Controllers;

use App\Litteraturkritikk\Person;
use Illuminate\Http\Request;

class LitteraturkritikkPersonController extends RecordController
{

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = [
            'person' => Person::withTrashed()->with(
                'records_as_forfatter',
                'records_as_forfatter.forfattere',
                'records_as_forfatter.kritikere',
                'records_as_kritiker',
                'records_as_kritiker.forfattere',
                'records_as_kritiker.kritikere'
            )->findOrFail($id),
        ];

        return response()->view('litteraturkritikk.persons.show', $data);
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
            'kjonnstyper' => $this->getKjonnstyper(),
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

        $this->validate($request, [
            'etternavn'    => 'required',
            'birth_year'   => 'numeric|max:2100',
        ]);

        $person->etternavn = $request->get('etternavn');
        $person->fornavn = $request->get('fornavn') ?: null;
        $person->pseudonym_for = $request->get('pseudonym_for') ?: null;
        $person->pseudonym = $request->get('pseudonym') ?: null;
        $person->kommentar = $request->get('kommentar') ?: null;
        $person->bibsys_id = $request->get('bibsys_id') ?: null;
        $person->birth_year = $request->get('birth_year') ?: null;
        $person->death_year = $request->get('death_year') ?: null;
        $person->kjonn = $request->get('kjonn') ?: null;

        $person->save();

        \DB::unprepared('REFRESH MATERIALIZED VIEW litteraturkritikk_records_search');

        return redirect()->action('LitteraturkritikkPersonController@show', $id)
            ->with('status', 'Personen ble lagret');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {
        $this->authorize('litteraturkritikk');

        if (!$request->get('confirm-deleteperson')) {
            return redirect()->back()
                ->with('status', 'Manglet bekreftelse');
        }

        $person = Person::findOrFail($id);
        $person->records()->detach();

        $person->delete();

        \DB::unprepared('REFRESH MATERIALIZED VIEW litteraturkritikk_records_search');

        return redirect()->action('LitteraturkritikkPersonController@show', $id)
            ->with('status', 'Personen ble slettet');
    }
}