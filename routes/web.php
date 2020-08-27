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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{hash}','ShortUrlController@access');

Route::get('/urls/{hash}','ShortUrlController@accessDetails');

Route::post('/urls','ShortUrlController@store');

Route::put('/urls/{hash}','ShortUrlController@update');

Route::delete('/urls/{hash}','ShortUrlController@delete');
