<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Lib\Location;
use Carbon\Carbon;

class Crime extends Model
{
    protected $fillable = ['address', 'description', 'class', 'active', 'lat', 'lng'];

    public static function firstOrCreate(array $options = [])
    {
      if (! is_null($instance = self::where($options)->first())) {
        return $instance;
      }

      $res = Location::geocode($options['address'] . ' Tulsa, OK');
      $geo = $res->results[0]->geometry->location;
      $options['lat'] = $geo->lat;
      $options['lng'] = $geo->lng;

      return parent::create($options);
    }

    public function scopeLikeSlug($query, $slug)
    {
      // Only get the last 3 months of data. Should be plenty
      return $query->select('*')
        ->where('created_at', '>=', Carbon::now()->subMonths(3)->toDateTimeString())
        ->where(function ($q) use ($slug) {
          foreach($this->getFiltersForSlug($slug) as $condition) {
            call_user_func_array([$q, 'orWhere'], $condition);
          }
        });
    }

    public function scopeNear($query, $lat, $lng, $distance = 0.25)
    {
      return $query->having('distance','<=', $distance)
               ->select(\DB::raw($this->getRadiusQueryForCoords($lat, $lng)))
               ->orderBy('distance','asc');
    }

    private function getFiltersForSlug($slug)
    {
      $filters = [
        'accidents' => [
          ['description','like','%inj%'],
          ['description','like','%col%']
        ],
        'assaults' => [
          ['description','like','%aslt%'],
          ['description','like','%assault%']
        ],
        'auto-thefts' =>[
          ['description','like','%theft%']
        ],
        'burglaries' => [
          ['description','like','%vehicle%'],
          ['description','like','%burglary%']
        ],
        'burglaries-from-vehicles' => [
          ['description','like','%abandoned%'],
          ['description','like','%vehicle%']
        ],
        'disturbances' => [
          ['description','like','%disturbance%']
        ],
        'hit-runs' => [
          ['description','like','%hit&amp;run%']
        ],
        'missing-persons' => [
          ['description','like','%missing%']
        ],
        'shootings' => [
          ['description','like','%shot%'],
          ['description','like','%shooting%']
        ]
      ];

      return $filters[$slug];
    }

    private function getRadiusQueryForCoords($lat, $lng)
    {
      return "*,
        (3963.17 * ACOS(COS(RADIANS($lat))
          * COS(RADIANS(lat))
          * COS(RADIANS($lng) - RADIANS(lng))
          + SIN(RADIANS($lat))
          * SIN(RADIANS(lat)))) AS distance";
    }
}
