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
Route::get('check', 'MainController@getRansCheck');
Route::get('heh', 'MainController@getRansHEH');
Route::get('r', 'MainController@getRecords');
Route::get('rr', 'MainController@getReferrals');
Route::get('ar', 'MainController@getAddReferral');
Route::get('p', 'MainController@getPayments');
Route::get('dr', 'MainController@getRansDelete');
Route::get('mp', 'MainController@getRansMark');
Route::get('vm', 'MainController@getRansMokije');
