<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $logGroup = 'pages';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->view('pages.index', [
            'pages' => Page::orderBy('slug')->get(),
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Page $page
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        if (!$page->exists) {
            return abort(404);
        }
        return response()->view('pages.show', [
            'page' => $page,
            'base' => $page->base,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Page $page
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        $this->authorize($page->permission);

        return response()->view('pages.edit', [
            'page' => $page,
            'base' => $page->base,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Page                $page
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $this->authorize($page->permission);

        $page->body = $request->body;
        $page->updated_by = $request->user()->id;
        $page->save();

        $this->log(
            'Oppdaterte <a href="%s">%s</a>.',
            action('PageController@show', ['page' => $page->slug]),
            $page->slug
        );

        return redirect()->action('PageController@show', ['page' => $page->slug])
            ->with('status', 'Siden ble lagret.');
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
