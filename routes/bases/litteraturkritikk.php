<?php

/*
|--------------------------------------------------------------------------
| Additional routes for the base "Norsk litteraturkritikk"
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

Route::post('person', 'PersonController@store');
Route::get('person/{id}', 'PersonController@show');

Route::middleware('can:litteraturkritikk')
    ->group(function() {
        // Place any routes that should only be available to authorized users here
        Route::get('person/{id}/edit', 'PersonController@edit');
        Route::put('person/{id}', 'PersonController@update');
        Route::delete('person/{id}', 'PersonController@destroy');
    });


Route::get('norsklitteraturkritikk', 'Controller@redirectToHome');
Route::get('norsk_litteraturkritikk', 'Controller@redirectToHome');
