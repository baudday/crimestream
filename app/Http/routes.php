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
    return view('home');
});

Route::get('/report', function (\Illuminate\Http\Request $request) {
    return view('report');
});

Route::get('/api/crimes', function() {
  $crimes = App\Crime::where('active', true)->get();
  return response()->json($crimes);
});

Route::group(['prefix' => 'api'], function() {
  Route::get('report', function(\Illuminate\Http\Request $request) {
    $q = "";
    if ($request->input('not_like')) {
      $not_like = implode(' and ', array_map(function($word) {
        return "description not like '%$word%'";
      }, explode(' ', $request->input('not_like'))));
      $q .= isset($not_like) ? "$not_like" : "";
    }
    if ($request->input('like')) {
      $like = implode(' or ', array_map(function($word) {
        return "description like '%$word%'";
      }, explode(' ', $request->input('like'))));

      $q .= isset($not_like) ? " and $like" : " $like";
    }

    $crimes = DB::select("select * from crimes where $q");
    return response()->json($crimes);
  });

  Route::get('filter', function(\Illuminate\Http\Request $request) {
    if (!$slug = $request->input('slug')) return;
    switch ($slug) {
      case 'accidents':
        $q = "description like '%inj%' or description like '%col%'";
        break;
      case 'assaults':
        $q = "description like '%aslt%' or description like '%assault%'";
        break;
      case 'auto-thefts':
        $q = "description like '%theft%'";
        break;
      case 'burglaries':
        $q = "description not like '%vehicle%' and description like '%burglary%'";
        break;
      case 'burglaries-from-vehicles':
        $q = "description not like '%abandoned%' and description like '%vehicle%'";
        break;
      case 'disturbances':
        $q = "description like '%disturbance%'";
        break;
      case 'hit-runs':
        $q = "description like '%hit&amp;run%'";
        break;
      case 'missing-persons':
        $q = "description like '%missing%'";
        break;
      case 'shootings':
        $q = "description like '%shot%' or description like '%shooting%'";
        break;
    }

    $crimes = DB::select("select * from crimes where $q");
    return response()->json($crimes);
  });
});
