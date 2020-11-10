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
Route::view('/passwordReset', 'email.password_reset')->middleware('auth')->name('passwordReset');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

<<<<<<< HEAD
Route::get('/user/{user}/verify/{token}', $user_path."UserController@verify")->name('user.verify');
=======


Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->middleware('verified');
Auth::routes();


>>>>>>> 11f3cde66fd4b8d8399d262cda9968a0e60ae8f2
