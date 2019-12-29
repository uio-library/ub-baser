<?php

namespace App\Http\Controllers\Admin;

use App\Base;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class BaseController extends Controller
{
    protected $logGroup = 'admin';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response()->view('admin.base.index', [
            'bases' => Base::get(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Base $base
     * @return Response
     */
    public function show(Base $base)
    {
        return response()->view('admin.base.show', [
            'base' => $base,
        ]);
    }
}
