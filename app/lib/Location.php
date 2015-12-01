<?php

namespace App\Lib;

use \GuzzleHttp\Client;

class Location {

  const API_URL = 'https://maps.googleapis.com/maps/api/geocode/json';

  public static function geocode($q)
  {
    $query_data = [
      'address' => $q,
      'key' => env('GOOGLE_API_KEY')
    ];

    $client = new Client();
    $res = $client->request('GET', self::API_URL, ['query' => $query_data]);

    return json_decode($res->getBody());
  }

}
