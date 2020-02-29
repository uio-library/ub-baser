<?php

namespace App\Http\Controllers;

use App\Base;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $redirectTo = $request->session()->pull('url.intended');
        if (!is_null($redirectTo)) {
            return redirect($redirectTo);
        }

        return view('home', [
            'bases' => Base::get(),
        ]);
    }
}
