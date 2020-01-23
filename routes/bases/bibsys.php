<?php

/*
|--------------------------------------------------------------------------
| Additional routes for the base "Bibsys"
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

// Route::get('hello-world', 'Controller@helloWorld');
//// Bibsys
//
//Route::get('bibsys', 'Controller@index');
//Route::get('bibsys/autocomplete', 'Controller@autocomplete');
//Route::get('bibsys/{dokid}', 'Controller@show');
//

Route::middleware('can:bibsys')
    ->group(function () {
        // Place any routes that should only be available to authorized users here
    });
