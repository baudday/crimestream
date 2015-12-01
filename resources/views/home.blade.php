@extends('layouts.default')
@section('head-stuff')
<style>
  #map {
    height: 100vh;
  }
</style>
@stop

@section('body')
<div id="map"></div>
@stop

@section('body-scripts')
<script type="text/javascript">
  $(function() {
    $('#map').height(window.height);
    toastr.options.preventDuplicates = true;
    drawMap();
    drawCrimes();
    setInterval(drawCrimes, 60000);
  });

  function drawMap() {
    window.markers = [];
    window.map = L.map('map').setView([36.1314, -95.9372], 12);
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
      attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
      minZoom: 12,
      id: 'mapbox.light',
      accessToken: 'pk.eyJ1IjoiYmF1ZGRheSIsImEiOiJBWkFQV2NJIn0.k1sZSEElIyTFmvVLemkZnA'
    }).addTo(window.map);
    window.map.setMaxBounds([
      [36.23928898672854, -95.77812194824219], // NE
      [36.023557373379276, -96.09638214111328] // SW
    ]);
  }

  function drawCrimes() {
    toastr.info('Fetching fresh data...');
    $.ajax({
      'url': '/api/crimes'
    }).success(function(data) {
      toastr.clear();
      toastr.success(data.length + " calls in progress.");
      window.oldCrimes = window.newCrimes || null;
      window.newCrimes = data;
      eraseExpiredCrimes();
      $.each(data, function(i, crime) {
        var icon = L.MakiMarkers.icon({icon: getIcon(crime.class), color: getColor(crime.class), size: "m"});
        var marker = L.marker([crime.lat, crime.lng], {icon: icon});
        if (!markerExists(marker)) {
          marker.bindPopup("<b>" + crime.description + "</b><br />" + crime.address + "<br /><small>" + crime.class + "</small>");
          marker.addTo(window.map);
          window.markers.push(marker);
        }
      });
    }).error(function(data) {
      toastr.clear();
      toastr.error("Couldn't get fresh data. Will try again in 60 seconds.");
    })
  }

  function eraseExpiredCrimes() {
    if (!window.oldCrimes) return;

    $.each(window.markers, function(i, m) {
      var found = false;
      $.each(window.newCrimes, function(i, crime) {
        if (crime.lat == m.getLatLng().lat && crime.lng == m.getLatLng().lng) {
          found = true;
        }
      });
      if (!found) {
        window.map.removeLayer(m);
      }
    });
  }

  function markerExists(marker) {
    var found = false;
    $.each(window.markers, function(i, m) {
      latLng = m.getLatLng();
      if (latLng.lat == marker.getLatLng().lat && latLng.lng == marker.getLatLng().lng) {
        found = true;
      }
    });
    return found;
  }

  function getColor(markerClass) {
    switch (markerClass) {
      case "serious":
        return "#c0392b";
      break;
      case "accident":
        return "#e67e22";
      break;
      case "not_serious":
        return "#3498db";
      break;
      case "other":
        return "#7f8c8d";
      break;
    }
  }

  function getIcon(markerClass) {
    switch (markerClass) {
      case "serious":
        return "danger";
      break;
      case "accident":
        return "car";
      break;
      case "not_serious":
      case "other":
        return null;
      break;
    }
  }
</script>
@stop
