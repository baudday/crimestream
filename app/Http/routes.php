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

Route::get('/', 'Pages@home');

Route::get('/about', 'Pages@about');

Route::get('/report', 'Report@index');

Route::get('/address-lookup', 'Search@index');


Route::group(['prefix' => 'api'], function() {

  Route::group(['middleware' => 'cors'], function() {

    Route::get('crimes', 'Api\Crimes@index');
    Route::get('alerts', 'Api\Alert@index');
    Route::get('filter', 'Api\Filter@index');
    Route::get('radius', 'Api\Filter@radius');

  });


});
