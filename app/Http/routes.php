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

Route::group(['middleware' => 'auth'], function() {
  Route::get('/address-lookup', 'Search@index');
  Route::get('/heatmaps', 'Report@index');
  Route::get('/account', 'UsersController@edit');
  Route::put('/user/{id}', 'UsersController@update');

  Route::group(['prefix' => 'subscription'], function() {
    Route::post('create', 'SubscriptionsController@create');
    Route::get('cancel', 'SubscriptionsController@cancel');
    Route::get('resume', 'SubscriptionsController@resume');
    Route::put('update', 'SubscriptionsController@update');
  });
});


Route::group(['prefix' => 'api'], function() {

  Route::group(['middleware' => 'cors'], function() {

    Route::get('crimes', 'Api\Crimes@index');
    Route::get('alerts', 'Api\Alert@index');
    Route::get('filter', 'Api\Filter@index');
    Route::get('radius', 'Api\Filter@radius');

  });


});

Route::post(
  'stripe/webhook',
  '\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook'
);
