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

// User
Route::group(['middleware' => 'auth'], function () {
    Route::get('account', 'AccountController@index');
    Route::get('log', 'LogEntryController@index');
});

// Admin routes...
Route::get('admin/users/{id}', 'Admin\UserController@show');
Route::group(['middleware' => 'admin'], function () {

    Route::get('admin', 'Admin\AdminController@index');
    Route::get('admin/users', 'Admin\UserController@index');
    Route::get('admin/users/create', 'Admin\UserController@create');
    Route::get('admin/users/{id}/edit', 'Admin\UserController@edit');
    Route::post('admin/users/{id}', 'Admin\UserController@update');
    Route::post('admin/users', 'Admin\UserController@store');
    Route::post('admin/users/delete/{id}', 'Admin\UserController@destroy');

    Route::get('admin/pages', 'PageController@index');
});

Route::get('saml2/error', 'Auth\LoginController@samlError');
Route::get('saml2/register', 'Auth\LoginController@samlRegister');
Route::post('saml2/store', 'Auth\LoginController@samlStoreNewUser');

Auth::routes();

// Norsk litteraturkritikk
Route::get('norsklitteraturkritikk', 'LitteraturkritikkController@redir');
Route::get('norsk-litteraturkritikk', 'LitteraturkritikkController@redir');
Route::get('norsk-litteraturkritikk', 'LitteraturkritikkController@index');
Route::get('norsk-litteraturkritikk/autocomplete', 'LitteraturkritikkController@autocomplete');
Route::get('norsk-litteraturkritikk/create', 'LitteraturkritikkController@create');
Route::post('norsk-litteraturkritikk', 'LitteraturkritikkController@store');
Route::get('norsk-litteraturkritikk/{id}', 'LitteraturkritikkController@show');
Route::get('norsk-litteraturkritikk/{id}/edit', 'LitteraturkritikkController@edit');
Route::put('norsk-litteraturkritikk/{id}', 'LitteraturkritikkController@update');
Route::delete('norsk-litteraturkritikk/{id}', 'LitteraturkritikkController@destroy');
Route::post('norsk-litteraturkritikk/{id}/restore', 'LitteraturkritikkController@restore');

Route::post('norsk-litteraturkritikk/personer', 'LitteraturkritikkPersonController@store');
Route::get('norsk-litteraturkritikk/personer/{id}', 'LitteraturkritikkPersonController@show');
Route::get('norsk-litteraturkritikk/personer/{id}/edit', 'LitteraturkritikkPersonController@edit');
Route::put('norsk-litteraturkritikk/personer/{id}', 'LitteraturkritikkPersonController@update');
Route::delete('norsk-litteraturkritikk/personer/{id}', 'LitteraturkritikkPersonController@destroy');

// Dommer

Route::get('dommer', 'DommerController@index');
Route::get('dommer/autocomplete', 'DommerController@autocomplete');
Route::get('dommer/create', 'DommerController@create');
Route::post('dommer', 'DommerController@store');
Route::get('dommer/{id}', 'DommerController@show');
Route::get('dommer/{id}/edit', 'DommerController@edit');
Route::put('dommer/{id}', 'DommerController@update');

// Letras

Route::get('letras', 'LetrasController@index');
Route::get('letras/autocomplete', 'LetrasController@autocomplete');
Route::get('letras/create', 'LetrasController@create');
Route::post('letras', 'LetrasController@store');
Route::get('letras/{id}', 'LetrasController@show');
Route::get('letras/{id}/edit', 'LetrasController@edit');
Route::put('letras/{id}', 'LetrasController@update');

// Opes

Route::resource('opes', 'OpesController');

// Static pages

Route::group(['middleware' => 'admin'], function () {
    Route::get('{page}/edit', 'PageController@edit');
    Route::post('{page}/update', 'PageController@update');
});

Route::get('{page}', 'PageController@show');
