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
<script src="js/heatmap.js"></script>
<script src="js/leaflet-heatmap.js"></script>
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
      var cfg = {
        // radius should be small ONLY if scaleRadius is true (or small radius is intended)
        // if scaleRadius is false it will be the constant radius used in pixels
        "radius": .005,
        "maxOpacity": .5,
        // scales the radius based on map zoom
        "scaleRadius": true,
        // if set to false the heatmap uses the global maximum for colorization
        // if activated: uses the data maximum within the current map boundaries
        //   (there will always be a red spot with useLocalExtremas true)
        "useLocalExtrema": true,
        // which field name in your data represents the latitude - default "lat"
        latField: 'lat',
        // which field name in your data represents the longitude - default "lng"
        lngField: 'lng',
        // which field name in your data represents the data value - default "value"
        valueField: 'val'
      };


      var heatmapLayer = new HeatmapOverlay(cfg);
      var heat = {
        data: data.map(function(el) {
          return {lat: el.lat, lng: el.lng, val: 1};
        })
      };
      window.map.addLayer(heatmapLayer);
      heatmapLayer.setData(heat);
    }).error(function(data) {

    })
  }
</script>
@stop
