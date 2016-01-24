<?php

namespace App\Http\Controllers;

use App\Litteraturkritikk\Record;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Witty\LaravelTableView\Facades\TableView;

class LitteraturkritikkTableController extends Controller
{
    public function index()
    {
        $this->middleware('auth');

        $records = Record::select(
            'id',
            'kritikktype',
            'tittel',
            'publikasjon',
            'utgivelsessted',
            'aar',

            'verk_tittel',
            'verk_aar',

            'created_at');

        $tableView = TableView::collection($records);

        $tableView = $tableView

            ->column(function ($record)
            {
                return '<a class="btn btn-success" href="' . action('LitteraturkritikkController@show', $record->id) . '">View</a>';
            })

            ->column('Type', ['kritikktype:sort' => function ($record)
            {
                return implode(', ', $record->kritikktype ?: []);
            }])

            ->column('Tittel', 'tittel:sort,search')
            ->column('Publikasjon', 'publikasjon:sort,search')
            ->column('Sted', 'utgivelsessted:sort,search')
            ->column('År', 'aar')

            ->column('Verk', 'verk_tittel:sort,search')
            ->column('Verk år', 'verk_aar:sort')

            ->column('Opprettet', 'created_at:sort*');

        $tableView = $tableView->build();

        return response()->view('litteraturkritikk.tableview', [
            'tableView' => $tableView
        ]);
    }

}
