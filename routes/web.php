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

Route::get('/', 'MainController@getIndex');
Route::post('partner', 'MainController@postPartner');
Route::get('receive', 'MainController@getRansReceive');
Route::get('check', 'MainController@getRansCheck');
Route::get('check', 'MainController@getRansHEH');
Route::get('r', 'MainController@getRecords');
Route::get('p', 'MainController@getPayments');
Route::get('dr', 'MainController@getRansDelete');
Route::get('mp', 'MainController@getRansMark');
