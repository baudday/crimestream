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

Route::group(['middleware' => 'guest'], function() {
  Route::get('auth/login', function() {
    return redirect('/')->with('showLoginForm', true);
  });
  Route::get('auth/{provider}', 'Auth\AuthController@redirectToProvider');
  Route::get('auth/{provider}/callback', 'Auth\AuthController@handleProviderCallback');
  Route::get('auth/logout', 'Auth\AuthController@getLogout');
});

Route::get('/', 'Pages@home');

Route::get('/about', 'Pages@about');

Route::get('/report', 'Report@index');

Route::get('/address-lookup', ['middleware' => 'auth', 'uses' => 'Search@index']);


Route::group(['prefix' => 'api'], function() {

  Route::group(['middleware' => 'cors'], function() {

    Route::get('crimes', 'Api\Crimes@index');
    Route::get('alerts', 'Api\Alert@index');
    Route::get('filter', 'Api\Filter@index');
    Route::get('radius', 'Api\Filter@radius');

  });


});
