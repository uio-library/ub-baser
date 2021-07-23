<?php

/*
|--------------------------------------------------------------------------
| Additional routes for the base "Bibliomanuel"
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

Route::middleware('can:nordskrift')
    ->group(function () {
        // Place any routes that should only be available to authorized users here
    });
