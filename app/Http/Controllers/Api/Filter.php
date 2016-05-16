<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Filter extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$slug = $request->input('slug')) return;
        $crimes = \App\Crime::likeSlug($slug)->get();
        return response()->json($crimes);
    }

    public function radius(Request $request)
    {
      $crimes = \App\Crime::near((float) $request->input('lat'), (float) $request->input('lng'))
        ->where('class', 'serious')
        ->where('created_at', '>=', Carbon::now()->subMonths(3)->toDateTimeString())
        ->get();

      $crime_counts = [];

      foreach ($crimes as $crime) {
        $key = $this->match($crime->description);
        if (array_key_exists($key, $crime_counts)) {
          $crime_counts[$key]++;
        }
        else {
          $crime_counts[$key] = 1;
        }
      }

      arsort($crime_counts);

      $average = Cache::get('crime_average');
      $count = count($crimes);

      return response()->json([
        'meta' => [
          'percent' => round(($count / $average) * 100),
          'counts' => array_merge( [ 'total' => $count ], $crime_counts )
        ],
        'crimes' => $crimes
      ]);
    }

    private function match($str)
    {
      $possible = [
        'burglary' => ['burglary', 'burglary from vehicle'],
        'robbery' => ['robbery', 'strong-arm'],
        'homicide' => 'homicide',
        'shooting' => ['shooting', 'shots'],
        'auto theft' => 'auto theft',
        'missing person' => 'missing person',
        'suicide' => ['doa', 'suicide'],
        'alarm holdup' => 'alarm holdup',
        'stabbing' => 'stabbing',
        'assault' => 'assault',
        'disturbance weapon' => 'disturbance weapon',
        'wanted subject' => 'wanted subject'
      ];

      $shortest = -1;
      foreach ($possible as $key => $value) {
        if (is_array($value)) {
          foreach ($value as $word) {
            $lev = levenshtein($str, $word);
            if ($lev == 0) return $key;
            if ($lev <= $shortest || $shortest < 0) {
              $closest = $key;
              $shortest = $lev;
            }
          }
        }
        else {
          $lev = levenshtein($str, $value);
          if ($lev == 0) return $key;
          if ($lev <= $shortest || $shortest < 0) {
            $closest = $key;
            $shortest = $lev;
          }
        }
      }
      return isset($closest) ? ucwords($closest) : $str;
    }
}
