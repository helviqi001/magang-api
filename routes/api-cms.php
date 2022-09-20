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
    'middleware' => 'token',
    'namespace' => 'App\Http\Controllers\CMS'
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
    'prefix' => 'manage/user'
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
    'prefix' => 'manage/role'
], function () {
    Route::get('/', 'RoleController@index');
    Route::post('/', 'RoleController@store');
    Route::get('/{id}', 'RoleController@show');
    Route::put('/{id}', 'RoleController@update');
    Route::delete('/{id}', 'RoleController@destroy');
});
