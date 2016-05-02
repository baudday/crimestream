@extends('layouts.default')
@section('head-stuff')
<link rel="stylesheet" href="css/simple-sidebar.css">
<link rel="stylesheet" href="css/reports-styles.css" media="screen" charset="utf-8">
@stop

@section('body')
<div id="wrapper" class="toggled">
  <div class="overlay" style="display: none;">
    <span class="helper"></span><img class="loader" src="img/loader.gif">
  </div>
  <div class="info-panel" id="sidebar-wrapper">
    <div class="sidebar-container">
      <div class="container-fluid sidebar-container">
        <div class="row-fluid">
          <div class="col-xs-12">
            <h1 id="address"></h1>
            <hr>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="page-content-wrapper">
    <div class="container-fluid map-container">
      <div class="row-fluid">
        <div class="col-xs-12" style="padding-left: 0;">
          <input type='text' id="address-input" class='form-control input-lg' placeholder='Address' tabindex="1" style="z-index: 99999; position: fixed; left: 10%; width: 80%; margin: 10px; border-radius: 0px;">
          <div id="map"></div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop

@section('body-scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
<script src="https://code.jquery.com/ui/1.12.0-rc.2/jquery-ui.min.js" integrity="sha256-55Jz3pBCF8z9jBO1qQ7cIf0L+neuPTD1u7Ytzrp2dqo=" crossorigin="anonymous"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.23&key=AIzaSyAkIPWgouXEvh_Zk7ny5WVg23oK9a3WhJg&libraries=places"></script>
<script type="text/javascript">
  var geocoder = new google.maps.Geocoder();
  var lastRequest = new Date().getTime();
  var bounds;
  var marker = L.marker([], { icon: L.MakiMarkers.icon({color: '#3498db', size: 'm'}) });
  var markers = [];
  $(function() {
    $('#address-input').focus();
    $('.info-panel').height(window.innerHeight - 70);
    $('#map').height(window.innerHeight - 70);
    window.map = L.map('map').setView([36.1314, -95.9372], 11);
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
      attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
      minZoom: 10,
      id: 'mapbox.light',
      accessToken: 'pk.eyJ1IjoiYmF1ZGRheSIsImEiOiJBWkFQV2NJIn0.k1sZSEElIyTFmvVLemkZnA'
    }).addTo(window.map);
    var sw = window.map.getBounds().getSouthWest();
    var ne = window.map.getBounds().getNorthEast();
    bounds = new google.maps.LatLngBounds({lat: sw.lat, lng: sw.lng}, {lat: ne.lat, lng: ne.lng});
    var input = document.getElementById('address-input');
    autocomplete = new google.maps.places.Autocomplete(input, {
      bounds: bounds,
      types: ['address']
    });
    google.maps.event.addListener(autocomplete, 'place_changed', geocode);
  });

  function geocode() {
    var place = autocomplete.getPlace();
    if (place.hasOwnProperty('geometry')) {
      $('.overlay').show();
      var point = [place.geometry.location.lat(), place.geometry.location.lng()];
      marker.setLatLng(point);
      marker.addTo(window.map);
      marker.update();
      window.map.setView(point, 16);
      getCrimes(place.geometry.location.lat(), place.geometry.location.lng());
      $('.overlay').hide();
    }
  }

  function getCrimes(lat, lng) {
    $.ajax({
      url: '/api/radius',
      data: { lat: lat, lng: lng }
    }).success(function(data) {
      toastr.success(data.length + " serious calls within 1/4 mile of this address in the last 3 months.");
      $.each(markers, function(i, m) { window.map.removeLayer(m) });
      markers = [];
      $.each(data, function(i, crime) {
        var icon = L.MakiMarkers.icon({icon: 'police', color: '#c0392b', size: 'm'});
        var m = L.marker([crime.lat, crime.lng], {icon: icon});
        m.bindPopup("<b>" + crime.description + "</b><br />" + crime.address + "<br /><small>" + crime.class + "</small><br /><small>" + crime.created_at + "</small>");
        m.addTo(window.map);
        markers.push(m);
      });
    });
  }

  function draw(slug) {
    var slug = slug || 'accidents';
    $('.overlay').show();
    $.ajax({
      'url': '/api/filter',
      'data': {slug: slug}
    }).success(function(data) {
      toastr.success(data.length + " calls");
      var points = [];

      // Filter out improperly mapped accidents
      if(slug == "accidents" || slug == "hit-runs") data = _.reject(data, function(el) {
        return el.lat == 36.1539816 && el.lng == -95.992775;
      });

      _.each(data, function(point) {
        points.push({lat: point.lat, lng: point.lng, count: 0.5});
      });

      var cfg = {
        // radius should be small ONLY if scaleRadius is true (or small radius is intended)
        // if scaleRadius is false it will be the constant radius used in pixels
        "radius": 0.004,
        "maxOpacity": .8,
        // scales the radius based on map zoom
        "scaleRadius": true,
        // if set to false the heatmap uses the global maximum for colorization
        // if activated: uses the data maximum within the current map boundaries
        //   (there will always be a red spot with useLocalExtremas true)
        "useLocalExtrema": false,
        // which field name in your data represents the latitude - default "lat"
        latField: 'lat',
        // which field name in your data represents the longitude - default "lng"
        lngField: 'lng',
        // which field name in your data represents the data value - default "value"
        valueField: 'count'
      };

      if (window.heatmapLayer) window.map.removeLayer(window.heatmapLayer);
      window.heatmapLayer = new HeatmapOverlay(cfg);
      var heat = { data: points };
      window.map.addLayer(window.heatmapLayer);
      heatmapLayer.setData(heat);
    }).error(function(data) {
      toastr.error('Something went wrong :(');
    }).complete(function() {
      $('.overlay').hide();
    });
  }
</script>
@stop
