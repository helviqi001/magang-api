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
    'middleware' => 'auth.global',
    'namespace' => 'App\Http\Controllers'
], function () {
    Route::post('/file-uploader', 'FileUploadController@FileUploader');
});

Route::group([
    'middleware' => 'guest',
    'namespace' => 'App\Http\Controllers'
], function () {
    Route::post('/token', 'CredentialController@AuthSystem');
});

Route::group([
    'middleware' => 'token',
    'namespace' => 'App\Http\Controllers\API'
], function () {
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login');
    Route::post('/logout', 'AuthController@logout');
    // Route::get('email/verify/{customer_id}', [VerificationController::class, 'verify'])->name('verification.verify');
    // Route::get('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
    Route::post('password/email', 'ForgotPasswordController@forgot');
    Route::post('password/reset', 'ForgotPasswordController@forgot');
});

Route::group([
    'middleware' => 'token',
    'namespace' => 'App\Http\Controllers\API'
], function () {
    Route::get('/wisata', 'WisataController@index');
    Route::post('/wisata', 'WisataController@store');
    Route::get('/wisata/{id}', 'WisataController@show');
    Route::put('/wisata/{id}', 'WisataController@update');
    Route::delete('/wisata/{id}', 'WisataController@destroy');
});

Route::group([
    'middleware' => 'token',
    'namespace' => 'App\Http\Controllers\API'
], function () {
    Route::get('/kuliner', 'KulinerController@index');
    Route::post('/kuliner', 'KulinerController@store');
    Route::get('/kuliner/{id}', 'KulinerController@show');
    Route::put('/kuliner/{id}', 'KulinerController@update');
    Route::delete('/kuliner/{id}', 'KulinerController@destroy');
});