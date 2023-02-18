<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

 Route::group([
    'middleware' => 'guest',
    'namespace' => 'App\Http\Controllers'
], function () {
    Route::post('/token', 'CredentialController@AuthSystem');
});
Route::group([
    'middleware' => 'token',
    'namespace' => 'App\Http\Controllers\CMS',
], function () {
    Route::post('/login', 'AuthController@login');
});

Route::group([
    'middleware' => 'auth.backoffice',
    'namespace' => 'App\Http\Controllers\Cms',
], function () {
    Route::get('/auth/my-privileges', 'AuthController@myPrivileges');
});

Route::group([
    'middleware' => 'auth.backoffice',
    'namespace' => 'App\Http\Controllers\CMS\Manage',
    'prefix' => 'manage/user',
], function () {
    Route::get('/', 'UserController@index');
    Route::post('/', 'UserController@store');
    Route::get('/{id}', 'UserController@show');
    Route::put('/{id}', 'UserController@update');
    Route::delete('/{id}', 'UserController@destroy');
});

Route::group([
    'middleware' => 'auth.backoffice',
    'namespace' => 'App\Http\Controllers\CMS\Manage',
    'prefix' => 'manage/role',
], function () {
    Route::get('/', 'RoleController@index');
    Route::post('/', 'RoleController@store');
    Route::get('/{id}', 'RoleController@show');
    Route::put('/{id}', 'RoleController@update');
    Route::delete('/{id}', 'RoleController@destroy');
});

Route::group([
    'middleware' => 'auth.backoffice',
    'namespace' => 'App\Http\Controllers\CMS\Manage',
    'prefix' => 'manage/menu-group',
], function () {
    Route::get('/', 'MenuGroupController@index');
    Route::post('/', 'MenuGroupController@store');
    Route::get('/{id}', 'MenuGroupController@show');
    Route::put('/{id}', 'MenuGroupController@update');
    Route::delete('/{id}', 'MenuGroupController@destroy');
});

Route::group([
    'middleware' => 'auth.backoffice',
    'namespace' => 'App\Http\Controllers\CMS\Manage',
    'prefix' => 'manage/menu-item',
], function () {
    Route::get('/', 'MenuItemController@index');
    Route::post('/', 'MenuItemController@store');
    Route::get('/{id}', 'MenuItemController@show');
    Route::put('/{id}', 'MenuItemController@update');
    Route::delete('/{id}', 'MenuItemController@destroy');
});

Route::group([
    'middleware' => 'auth.backoffice',
    'namespace' => 'App\Http\Controllers\CMS\Manage'
], function () {
    Route::get('/wisata', 'WisataController@index');
    Route::post('/wisata', 'WisataController@store');
    Route::get('/wisata/{id}', 'WisataController@show');
    Route::put('/wisata/{id}', 'WisataController@update');
    Route::delete('/wisata/{id}', 'WisataController@destroy');
});

Route::group([
    'middleware' => 'auth.backoffice',
    'namespace' => 'App\Http\Controllers\CMS\Manage'
], function () {
    Route::get('/kuliner', 'KulinerController@index');
    Route::post('/kuliner', 'KulinerController@store');
    Route::get('/kuliner/{id}', 'KulinerController@show');
    Route::put('/kuliner/{id}', 'KulinerController@update');
    Route::delete('/kuliner/{id}', 'KulinerController@destroy');
});
