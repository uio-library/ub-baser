<?php

namespace App\Http\Controllers;

class RecordController extends Controller
{
    /**
     * Instantiate a new RecordController instance.
     */
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['create', 'edit', 'store', 'update', 'destroy']]);
    }

    public function getKjonnstyper()
    {
        return [
            'f'  => 'Kvinne',
            'm'  => 'Mann',
            'u'  => 'Ukjent',
            // etc...
        ];
    }
}
