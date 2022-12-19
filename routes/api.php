<?php

use App\Http\Controllers\API\ForgotPasswordController;
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
   
    // Route::post('/forgotpassword',[ForgotPasswordController::class,'forgotPassword'])->name('forgotPassword');
    Route::post('/verify', 'ForgotPasswordController@verifyOtp');
    Route::post('/resend', 'ForgotPasswordController@resendOtp');
    Route::post('/logout', 'AuthController@logout');
});

Route::group([
    
    'namespace' => 'App\Http\Controllers\API'
],  function () {
    Route::get('/forgotpassword',[ForgotPasswordController::class,'showForgotPassword'])->name('showForgotPassword');
    Route::post('/forgotpassword',[ForgotPasswordController::class,'forgotPassword'])->name('forgotPassword');
   
});



Route::group([
    'middleware' => 'token',
    'namespace' => 'App\Http\Controllers\API'
], function () {
    Route::get('/wisata', 'WisataController@index');
    Route::get('/wisata/{id}', 'WisataController@show');
});

Route::group([
    'middleware' => 'token',
    'namespace' => 'App\Http\Controllers\API'
], function () {
    Route::get('/kuliner', 'KulinerController@index');
    Route::get('/kuliner/{id}', 'KulinerController@show');
});