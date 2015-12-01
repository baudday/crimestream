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

Route::get('/api/crimes', function() {
  $client = new GuzzleHttp\Client();
  $res = $client->get("https://www.tulsapolice.org/live-calls-/police-calls-near-you.aspx");
  $data = (string) $res->getBody();

  if ($res->getStatusCode() != 200) {
    return response()->json(['error' => 'Couldn\'t retrieve crime data'], 500);
  }

  $classes = [
    'accident' => ['/coll/','/collision/','/crash/'],
    'serious' => ['/burglary/','/robbery/','/homicide/','/shooting/','/shots/','/theft/','/missing/','/intrusion/','/doa/','/suicide/'],
    'not_serious' => ['/disturbance/']
  ];

  preg_match_all('/<td .+>(.+)<\/td><td>(.+)<\/td>/sU', $data, $matches);

  $scraped_crimes = [];

  foreach ($matches[1] as $key=>$val) {
    foreach ($classes as $class=>$patterns) {
      foreach ($patterns as $pattern) {
        if (preg_match($pattern, strtolower($val))) {
          $class_val = $class;
        }
      }
    }

    $crime = [
      'description' => $val,
      'address' => ucwords(strtolower($matches[2][$key])),
      'class' => isset($class_val) ? $class_val : 'other'
    ];

    $scraped_crimes[] = $crime;
    App\Crime::firstOrCreate($crime);
    unset($class_val);
  }

  $active_crimes = App\Crime::where('active', true)->select('description', 'address', 'class')->get();
  $expired_crimes = array_merge(
    array_udiff($active_crimes->toArray(), $scraped_crimes, function($a, $b) {
      return strcasecmp($a['address'], $b['address']);
    }),
    array_udiff($scraped_crimes, $active_crimes->toArray(), function ($b, $a) {
      return strcasecmp($b['address'], $a['address']);
    })
  );

  foreach ($expired_crimes as $expired_crime) {
    App\Crime::where($expired_crime)->update(['active' => false]);
  }

  $crimes = App\Crime::where('active', true)->get();

  return response()->json($crimes);
});
