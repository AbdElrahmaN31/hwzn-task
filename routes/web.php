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

Route::middleware('block')->get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware('block')->namespace('App\Http\Controllers\Web')->group(function () {
    Route::get('/users', 'UserController@index')->name('users');
    Route::get('/login', 'UserController@showLoginForm')->name('login');
    Route::post('/login', 'UserController@login');
});

Route::get('/blocked', function () {
    return view('blocked');
})->name('blocked');
