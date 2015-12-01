<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Lib\Location;

class Crime extends Model
{
    protected $fillable = ['address', 'description', 'class', 'active'];

    public function save(array $options = [])
    {
      $res = Location::geocode($this->address . ' Tulsa, OK');
      $geo = $res->results[0]->geometry->location;
      $this->lat = $geo->lat;
      $this->lng = $geo->lng;
      parent::save($options);
    }
}
