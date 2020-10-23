<?php

use Illuminate\Support\Facades\Route;

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

$user_path = 'api\v1\user\\';

Route::view('/', 'welcome');
Auth::routes(['verify' => true]);



Route::view('/home', 'home')->middleware('auth','verified');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/user/{user}/verify/{token}', $user_path."UserController@verify")->name('user.verify');
