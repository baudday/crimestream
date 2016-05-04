<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Lib\Location;

class Crime extends Model
{
    protected $fillable = ['address', 'description', 'class', 'active', 'lat', 'lng'];

    public static function create(array $options = [])
    {
      $res = Location::geocode($options['address'] . ' Tulsa, OK');
      $geo = $res->results[0]->geometry->location;
      $options['lat'] = $geo->lat;
      $options['lng'] = $geo->lng;
      return parent::create($options);
    }

    public function scopeNear($query, $lat, $lng, $distance = 0.25)
    {
      return $query->having('distance','<=', $distance)->select(\DB::raw("*,
        (3963.17 * ACOS(COS(RADIANS($lat))
          * COS(RADIANS(lat))
          * COS(RADIANS($lng) - RADIANS(lng))
          + SIN(RADIANS($lat))
          * SIN(RADIANS(lat)))) AS distance")
        )->orderBy('distance','asc');
    }
}
