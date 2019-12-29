<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the `RouteServiceProvider` within a group
| which contains the "web" middleware group.
|
*/

Route::get('/', 'HomeController@index')->name('home');

// User
Route::group(['middleware' => 'auth'], function () {
    Route::get('account', 'AccountController@index');
    Route::get('log', 'LogEntryController@index');
});

// Admin routes...
Route::get('users/{id}', 'Admin\UserController@show');
Route::group(['middleware' => 'admin'], function () {
    Route::get('admin', 'Admin\AdminController@index');

    // Manage users
    Route::get('admin/users', 'Admin\UserController@index');
    Route::get('admin/users/create', 'Admin\UserController@create');
    Route::get('admin/users/{id}', 'Admin\UserController@edit');
    Route::post('admin/users/{id}', 'Admin\UserController@update');
    Route::post('admin/users', 'Admin\UserController@store');
    Route::post('admin/users/delete/{id}', 'Admin\UserController@destroy');

    // Manage pages
    Route::get('admin/pages', 'PageController@index');

    // Manage bases
    Route::get('admin/bases', 'Admin\BaseController@index');
    Route::get('admin/bases/{base}', 'Admin\BaseController@show');
});

Route::get('saml2/error', 'Auth\LoginController@samlError');
Route::get('saml2/register', 'Auth\LoginController@samlRegister');
Route::post('saml2/store', 'Auth\LoginController@samlStoreNewUser');

// Authentication Routes
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

