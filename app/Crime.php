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
}
