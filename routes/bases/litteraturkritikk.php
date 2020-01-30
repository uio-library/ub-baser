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

Route::get('nb-search', 'Controller@nationalLibrarySearch');
Route::get('list', 'Controller@listIndex');
Route::get('list/{id}', 'Controller@listShow');

Route::get('person/{id}', 'PersonController@show');
Route::get('work/{id}', 'WorkController@show');

Route::middleware('can:litteraturkritikk')
    ->group(function () {
        // Place any routes that should only be available to authorized users here

        Route::post('person', 'PersonController@store');
        Route::get('person/{id}/edit', 'PersonController@edit');
        Route::put('person/{id}', 'PersonController@update');
        Route::get('person/{id}/delete', 'PersonController@delete');
        Route::delete('person/{id}', 'PersonController@destroy');

        Route::post('work', 'WorkController@store');
        Route::get('work/{id}/edit', 'WorkController@edit');
        Route::put('work/{id}', 'WorkController@update');
        // Route::get('work/{id}/delete', 'WorkController@delete');
        // Route::delete('work/{id}', 'WorkController@destroy');
    });

Route::get('norsklitteraturkritikk', 'Controller@redirectToHome');
Route::get('norsk_litteraturkritikk', 'Controller@redirectToHome');
