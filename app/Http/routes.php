<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::group(['middleware' => ['web']], function () {
    //
});

Route::group(['prefix' => '/main/serve'], function () {
    Route::resource('/ussd', 'UssdController@index');
    Route::resource('/sms', 'UssdController@getSms');
    Route::resource('/call', 'UssdController@getCall');
    Route::resource('/airtime', 'UssdController@getAirtime');
});
