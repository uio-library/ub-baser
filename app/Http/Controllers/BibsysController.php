<?php

namespace App\Http\Controllers;

use App\Http\Requests\BibsysSearchRequest;
use App\Bibsys\BibsysDokument;
use App\Bibsys\BibsysView;
use App\Bibsys\BibsysSchema;
use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class BibsysController extends RecordController
{
    protected $logGroup = 'bibsys';

    /**
     * Display a listing of the resource.
     *
     *
     * @param BibsysSearchRequest $request
     * @param BibsysSchema $schema
     * @return \Illuminate\Http\Response
     */
    public function index(BibsysSearchRequest $request, BibsysSchema $schema)
    {
        if ($request->wantsJson()) {
            return $this->dataTablesResponse($request, $schema);
        }

        $introPage = Page::where('slug', '=', 'bibsys/intro')->first();
        $intro = $introPage ? $introPage->body : '';

        return response()->view('bibsys.index', [
            'schema' => $schema,

            'query' => $request->all(),
            'processedQuery' => $request->queryParts,
            'advancedSearch' => ($request->advanced === 'on'),

            'intro' => $intro,

            'defaultColumns' => [

                'dokid',
                'avdeling',
                'samling',
                'hyllesignatur',
                'title_statement',
                'pub_date',

            ],
            'order' => [
                ['key' => 'dokid', 'direction' => 'asc'],
            ],
        ]);
    }

    public function show(BibsysSchema $schema, $id)
    {
        $record = BibsysView::findOrFail($id);

        $record->marc_record_formatted = preg_replace(
            ['/^\*/m', '/\n/', '/\$([a-z0-9])/'],
            ['', '<br>', '<span style="font-weight: bold">$\1 </span>'],
            $record->marc_record
        );

        $data = [
            'schema' => $schema,
            'record'  => $record,
        ];

        return response()->view('bibsys.show', $data);
    }

    public function autocomplete(Request $request,  BibsysSchema $schema)
    {
        $fieldName = $request->get('field');
        $fields = $schema->keyed();
        if (!isset($fields[$fieldName])) {
            throw new \RuntimeException('Invalid field');
        }
        $field = $fields[$fieldName];
        $index = $field->get('searchOptions.index', [
            'column' => $field->key,
        ]);

        $term = $request->get('q') . '%';
        $data = [];

        switch ($fieldName) {
            default:
                if ($term == '%') {
                    // Preload request
                    $rows = \DB::table('bibsys_search')
                        ->select($field->getColumn())
                        ->groupBy($field->getColumn())
                        ->get();
                } else {
                    $rows = \DB::table('bibsys_search')
                        ->select($field->getColumn())
                        ->whereRaw($index['column'] . ' like ?', [$term])
                        ->groupBy($field->getColumn())
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

}
