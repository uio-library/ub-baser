<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');

// Autocomplete routes
Route::get('norsk-litteraturkritikk/autocomplete', 'LitteraturkritikkController@autocomplete');
Route::get('letras/autocomplete', 'LetrasController@autocomplete');
Route::get('dommer/autocomplete', 'DommerController@autocomplete');

// Page routes
Route::group(['middleware' => 'admin'], function () {
    Route::get('{page}/edit', 'PageController@edit')->where('page', '[a-z-]+/[a-z-]+');
    Route::post('{page}/update', 'PageController@update')->where('page', '[a-z-]+/[a-z-]+');
});
Route::get('{page}', 'PageController@show')->where('page', '[a-z-]+/[a-z-]+');

// Resource routes
Route::resource('norsk-litteraturkritikk', 'LitteraturkritikkController');
Route::resource('norsk-litteraturkritikk/personer', 'LitteraturkritikkPersonController');
Route::resource('dommer', 'DommerController');
Route::resource('letras', 'LetrasController');
Route::resource('opes', 'OpesController');

// User
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

Auth::routes();
