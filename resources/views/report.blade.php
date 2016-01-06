@extends('layouts.default')
@section('head-stuff')
<style>
  #map { position:absolute; top:70px; bottom:0; width:100%; }
</style>
@stop

@section('body')
<div id="map"></div>
@stop

@section('body-scripts')
<script src="js/leaflet-heat.js"></script>
<script type="text/javascript">
  $(function() {
    $('#map').height(window.height);
    drawMap();
    draw();
  });

  function drawMap() {
    window.map = L.map('map').setView([36.1314, -95.9372], 12);
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
      attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
      minZoom: 10,
      id: 'mapbox.light',
      accessToken: 'pk.eyJ1IjoiYmF1ZGRheSIsImEiOiJBWkFQV2NJIn0.k1sZSEElIyTFmvVLemkZnA'
    }).addTo(window.map);
  }

  function draw() {
    $.ajax({
      'url': '/api/report',
      'data': {!! json_encode($report_params) !!}
    }).success(function(data) {
      var heat = data.map(function(el) {
        return [el.lat, el.lng];
      });
      L.heatLayer(heat, {radius: 25}).addTo(window.map);
    }).error(function(data) {

    })
  }
</script>
@stop
