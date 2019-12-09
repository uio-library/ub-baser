<?php

namespace App\Http\Controllers;

class AccountController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Account controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the account settings for users
    |
    */

    public function index()
    {
        $data = [
            'user' => \Auth::user(),
        ];

        return response()->view('account.index', $data);
    }
}
