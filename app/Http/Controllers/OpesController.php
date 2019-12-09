<?php
namespace App\Http\Controllers;

use App\PubOpes;
use App\OpesRecord;
use App\RecordQueryBuilderOpes;
use Illuminate\Http\Request;

class OpesController extends RecordController
{
    
    // ny function laget 2 august 2016
    // henter ut verdier for alle publication for hver
    //
    protected function getPublications()
    {
        $publi = [];
        foreach (PubOpes::all() as $publi) {
            $publi[$publi->id] = $publi->Ser_Vol;
        }
    
        return $publi;
    }




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    public function index(Request $request)
    {
        
        $q = new RecordQueryBuilderOpes($request, 'opes', OpesRecord::class);
        $q->make();
        $data = [
            'prefix' => 'opes',
            'query' => $request->all(),
            'columns' => $q->getColumns(),
            'sortColumn' => $q->sortColumn,
            'sortOrder' => $q->sortOrder,
        ];
        //
        $data['records'] = OpesRecord::paginate(50);

        /* $q->query
          ->join('opes_pub', 'opes.id', '=', 'opes_pub.opes_id')
          //join('opes', 'opes_pub.papy_id', '=', 'opes.id')
          ->select('opes_pub.*', 'opes.*')
          // Dan henter verdier   opes_pub.Ser_Vol
         // join('opes_pub', 'opes.id', '=', 'opes_pub.papy_id')
          // 'opes_pub', 'opes.id', '=', 'opes_pub.papy_id' */


            // vi vet ikke hva dette er....
        $data['publikasjoner'] = $this->getPublications();


        return response()->view('opes.index', $data);
    }









    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /* protected function updateOrCreate(Request $request, $id = null)
    {
        $record = is_null($id) ? new LetrasRecord() : LetrasRecord::findOrFail($id);
        $this->validate($request, [
            'forfatter'     => 'required' . (is_null($id) ? '' : ',' . $id) . '|max:255',
            'land'      => 'required',
            'tittel'     => 'required',
            'utgivelsesaar' => 'required',
            'sjanger' => 'required',
            'oversetter' => 'required',
            'tittel2' => 'required',
            'utgivelsessted' => 'required',
            'utgivelsesaar2' => 'required',
            'forlag' => 'required',
            'foretterord' => 'required',
            'spraak' => 'required',
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
    } */

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    /* public function create()
    {
        $this->authorize('opes');
        $data = [
            'columns' => config('baser.opes.columns'),
        ];
        return response()->view('opes.create', $data);
    } */


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    
/*
    public function store(Request $request)
    {
        $this->authorize('opes');
        $record = $this->updateOrCreate($request);
        return redirect()->action('OpesController@show', $record->id)
            ->with('status', 'Posten ble opprettet.');
    }
*/

    public function show($id)
    {
        $record = OpesRecord::findOrFail($id);
     
        $data = [
            'columns' => config('baser.opes.columns'),
            'record'  => $record,
        ];
        return response()->view('opes.show', $data);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */

    /* public function edit($id)
    {
        $this->authorize('opes');
        $record = OpesRecord::findOrFail($id);
        $data = [
            'record'   => $record,
        ];
        return response()->view('opes.edit', $data);
    } */

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    
    /* public function update(Request $request, $id)
    {
        $this->authorize('opes');
        $this->updateOrCreate($request, $id);
        return redirect()->action('OpesController@show', $id)
            ->with('status', 'Posten ble lagret');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */

    /* public function destroy($id)
    {
        $this->authorize('opes');
        //
    } */
}
