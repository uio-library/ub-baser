<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Additional routes for the base "OPES"
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

Route::get('edition/{id}', 'EditionController@show');

Route::middleware('can:opes')
    ->group(function () {
        // Place any routes that should only be available to authorized users here
        Route::post('record/{record}/publish', 'Controller@publish');
        Route::post('record/{record}/unpublish', 'Controller@unpublish');

        Route::post('pub', 'EditionController@store');
        Route::get('pub/{id}/edit', 'EditionController@edit');
        Route::put('pub/{id}', 'EditionController@update');
        Route::get('pub/{id}/delete', 'EditionController@delete');
        Route::delete('pub/{id}', 'EditionController@destroy');
    });
