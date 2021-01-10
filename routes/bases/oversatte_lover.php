<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Additional routes for the base "Oversatte lover"
|--------------------------------------------------------------------------
|
| This is the place to register additional routes for this base beyond the
| standard routes defined by the `mapDynamicRoutes` method in
| `RouteServiceProvider`.
|
| The routes are loaded by `RouteServiceProvider` within a group that
| sets middleware and namespace for this base.
|
| Tip: To list all routes, use the `route:list` artisan command:
|
|     ./dev.sh artisan route:list
*/

Route::get('lov/{id}', 'LovController@show');

Route::middleware('can:oversatte_lover')
    ->group(function () {
        // Place any routes that should only be available to authorized users here
        Route::post('lov', 'LovController@store');
        Route::get('lov/{id}/edit', 'LovController@edit');
        Route::put('lov/{id}', 'LovController@update');
        Route::get('lov/{id}/delete', 'LovController@delete');
        Route::delete('lov/{id}', 'LovController@destroy');
    });
