<?php

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::any('get_unique_id','ApiController@getUniqueId')->name('get_unique_id');

Route::any('add_call','ApiController@addDevice')->name('add_call');

Route::any('add_device','ApiController@addDeviceName')->name('add_device');

Route::any('get_call_history','ApiController@getCallHistory')->name('get_call_history');



