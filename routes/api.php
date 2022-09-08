<?php

use Illuminate\Http\Request;
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

Route::get('/', function () {
    return app()->version();
});

Route::group([
    'middleware' => 'guest',
], function () {
    Route::get('/login', 'LoginController@showLoginForm')->name('login');
    Route::post('/login', 'LoginController@authenticate')->name('authenticate');
});

Route::group([
    'middleware' => 'auth:api'
], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
