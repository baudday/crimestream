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
    $report_params = ['like' => $request->input('like'), 'not_like' => $request->input('not_like')];
    return view('report', compact('report_params'));
});

Route::get('/api/crimes', function() {
  $crimes = App\Crime::where('active', true)->get();
  return response()->json($crimes);
});

Route::get('/api/report', function(\Illuminate\Http\Request $request) {
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
