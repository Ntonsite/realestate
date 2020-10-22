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

$user_path = 'api\v1\user\\';
$property_path = 'api\v1\property\\';


Route::prefix('user')->group(function() use ($user_path){
    Route::post('register', $user_path."UserController@register");
    Route::post('login', $user_path."UserController@login");
    Route::middleware('auth:api')->get('logout', $user_path."UserController@logout");
    Route::middleware('auth:api')->get('list', $user_path."UserController@index");
    Route::middleware('auth:api')->get('show/{user}', $user_path."UserController@show");
    Route::middleware('auth:api')->patch('update/{user}', $user_path."UserController@update");
    Route::middleware('auth:api')->delete('delete/user', $user_path."UserController@destroy");
    Route::middleware('auth:api')->post('uploadImage/{user}', $user_path."UserController@uploadImage");
});

Route::prefix('property')->group(function() use ($property_path){
    Route::get('list', $property_path."PropertyController@index");
    Route::get('show/{property}', $property_path."PropertyController@show");
    Route::middleware('auth:api')->post('store', $property_path."PropertyController@store");
    Route::middleware('auth:api')->patch('update/{property}', $property_path."PropertyController@update");
    Route::middleware('auth:api')->delete('delete/{property}', $property_path."PropertyController@destroy");
});



//Route::prefix('/ad')->group(function(){
//    Route::middleware('auth:api')->get('/list', 'api\v1\AdController@index');
//    Route::middleware('auth:api')->get('/{id}', 'api\v1\AdController@show');
//    Route::middleware('auth:api')->post('/create', 'api\v1\AdController@store');
//    Route::middleware('auth:api')->put('/update/{id}', 'api\v1\AdController@update');
//    Route::middleware('auth:api')->delete('/delete/{id}', 'api\v1\AdController@destroy');
//});
