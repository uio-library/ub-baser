<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

Route::get('norsk-litteraturkritikk/search', 'LitteraturkritikkController@search');
Route::get('norsk-litteraturkritikk/tableview', 'LitteraturkritikkTableController@index');
Route::resource('norsk-litteraturkritikk', 'LitteraturkritikkController');
Route::resource('norsk-litteraturkritikk/personer', 'LitteraturkritikkPersonController');
Route::resource('dommer', 'DommerController');
Route::resource('letras', 'LetrasController');
Route::resource('opes', 'OpesController');

Route::group(['middleware' => 'auth'], function () {
    Route::get('account', 'AccountController@index');
});

// Admin routes...
Route::group(['middleware' => 'admin'], function () {

    Route::get('admin', 'Admin\AdminController@index');
    Route::get('admin/users', 'Admin\UserController@index');
    Route::get('admin/users/create', 'Admin\UserController@create');
    Route::get('admin/users/{id}', 'Admin\UserController@edit');
    Route::post('admin/users/{id}', 'Admin\UserController@update');
    Route::post('admin/users', 'Admin\UserController@store');
    Route::post('admin/users/delete/{id}', 'Admin\UserController@destroy');

    Route::get('admin/pages', 'PageController@index');

});
