<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
26th Sept: Ntonsite Mwamlima, compiled Final version
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// 05th October

Route::prefix('/user')->group(function(){
    Route::post('/login', 'api\v1\ApiController@login');
    Route::middleware('auth:api')->post('/register', 'api\v1\ApiController@register');
    Route::middleware('auth:api')->get('/logout', 'api\v1\ApiController@logout');
    Route::middleware('auth:api')->get('/list', 'api\v1\user\UserController@index');
    Route::middleware('auth:api')->put('/subscriptionAdd/{id}', 'api\v1\user\UserController@update');
});

Route::prefix('/ad')->group(function(){
    Route::middleware('auth:api')->get('/list', 'api\v1\AdController@index');
    Route::middleware('auth:api')->get('/{id}', 'api\v1\AdController@show');
    Route::middleware('auth:api')->post('/create', 'api\v1\AdController@store');
    Route::middleware('auth:api')->put('/update/{id}', 'api\v1\AdController@update');
    Route::middleware('auth:api')->delete('/delete/{id}', 'api\v1\AdController@destroy');
});
