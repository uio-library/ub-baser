<?php

namespace App\Bases\Litteraturkritikk;

use App\Base;
use App\Http\Controllers\BaseController;
use App\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class PersonController extends BaseController
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
     * Show a person record.
     *
     * @param Request $request
     * @param Base $base
     * @param $id
     * @return Response
     */
    public function show(Request $request, Base $base, $id)
    {
        return response()->view($base->getView('persons.show'), [
            'base' => $base,
            'person' => Person::withTrashed()->with(
                'recordsAsAuthor',
                'recordsAsAuthor.forfattere',
                'recordsAsAuthor.kritikere',
                'recordsAsCritic',
                'recordsAsCritic.forfattere',
                'recordsAsCritic.kritikere'
            )->findOrFail($id),
        ]);
    }

    /**
     * Show the form for editing a person record.
     *
     * @param Request $request
     * @param Base $base
     * @param $id
     * @return Response
     */
    public function edit(Request $request, Base $base, $id)
    {
        return response()->view($base->getView('persons.edit'), [
            'base' => $base,
            'person' => Person::findOrFail($id),
            'kjonnliste' => self::getGenderList(),
        ]);
    }

    /**
     * Create a new person record.
     *
     * @param Request $request
     * @param Base $base
     * @return JsonResponse
     */
    public function store(Request $request, Base $base)
    {
        $person = new Person();

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

        $url = $base->action('PersonController@show', $person->id);
        $this->log(
            'Opprettet <a href="%s">person #%s (%s)</a>.',
            $url,
            $person->id,
            "{$person->etternavn}, {$person->fornavn}"
        );

        return response()->json([
            'status' => 'ok',
            'record' => $person,
        ]);
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

        $url = $base->action('PersonController@show', $person->id);
        $this->log(
            'Oppdaterte <a href="%s">person #%s (%s)</a>.',
            $url,
            $person->id,
            "{$person->etternavn}, {$person->fornavn}"
        );

        return redirect($url)->with('status', 'Personen ble lagret.');
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param Base $base
     * @param $id
     * @return RedirectResponse
     */
    public function destroy(Request $request, Base $base, $id)
    {
        if (!$request->get('confirm-deleteperson')) {
            return redirect()->back()
                ->with('status', 'Manglet bekreftelse');
        }

        $person = Person::findOrFail($id);

        $url = $base->action('PersonController@show', $person->id);
        $this->log(
            'Slettet <a href="%s">person #%s (%s)</a>.',
            $url,
            $person->id,
            "{$person->etternavn}, {$person->fornavn}"
        );

        $person->records()->detach();

        $person->delete();

        return redirect($url)->with('status', 'Personen ble slettet og fjernet fra alle poster.');
    }
}
