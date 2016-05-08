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
        switch ($slug) {
            case 'accidents':
                $filter = [
                    ['description','like','%inj%'],
                    ['description','like','%col%'],
                ];
                break;
            case 'assaults':
                $filter = [
                    ['description','like','%aslt%'],
                    ['description','like','%assault%'],
                ];
                break;
            case 'auto-thefts':
                $filter = [
                    ['description','like','%theft%'],
                ];
                break;
            case 'burglaries':
                $filter = [
                    ['description','like','%vehicle%'],
                    ['description','like','%burglary%'],
                ];
                break;
            case 'burglaries-from-vehicles':
                $filter = [
                    ['description','like','%abandoned%'],
                    ['description','like','%vehicle%'],
                ];
                break;
            case 'disturbances':
                $filter = [
                    ['description','like','%disturbance%']
                ];
                break;
            case 'hit-runs':
                $filter = [
                    ['description','like','%hit&amp;run%']
                ];
                break;
            case 'missing-persons':
                $filter = [
                    ['description','like','%missing%']
                ];
                break;
            case 'shootings':
                $filter = [
                    ['description','like','%shot%'],
                    ['description','like','%shooting%'],
                ];
                break;
        }

        // Only get the last 3 months of data. Should be plenty
        $query = \App\Crime::select('*')
          ->where('created_at', '>=', Carbon::now()->subMonths(3)->toDateTimeString())
          ->where(function ($q) use ($filter) {
            foreach($filter as $condition) {
                call_user_func_array([$q, 'orWhere'], $condition);
            }
          });

        $crimes = $query->get();
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

      // TODO: Turn average calculation into a job!
      $average = \App\Crime::where('class', 'serious')->count() / (186.8 / 0.25);
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
