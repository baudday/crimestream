@extends('layouts.default')
@section('head-stuff')
<link rel="stylesheet" href="css/address-lookup.css" media="screen" charset="utf-8">
@stop

@section('body')
<div id="wrapper" class="toggled">
  <div class="overlay" style="display: none;">
    <span class="helper"></span><img class="loader" src="img/loader.gif">
  </div>
  <div id="page-content-wrapper">
    <div class="container-fluid map-container">
      <div class="row-fluid">
        <div class="col-xs-12" style="padding-left: 0;">
          <input type='text' id="address-input" class='form-control input-lg' placeholder='Address' tabindex="1">
          <div id="map"></div>
        </div>
      </div>
      <div class="row-fluid">
        <div class="col-xs-12 info">
          <div class="container">
            <div class="row">
              <div class="col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
                <h2 id="address"></h2>
                <hr>
                <p id="summary"></p>
                <h3>Top Reports</h3>
                <ul id="crime-counts"></ul>
              </div>
            </div>
          </div>
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
  var circle = L.circle([], 402.336);
  var markers = [];
  $(function() {
    $('#address-input').focus();
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
      circle.setLatLng(point);
      circle.addTo(window.map);
      window.map.setView(point, 15);
      getCrimes(place.geometry.location.lat(), place.geometry.location.lng());
      $('.overlay').hide();
    }
  }

  function getCrimes(lat, lng) {
    $.ajax({
      url: '/api/radius',
      data: { lat: lat, lng: lng }
    }).success(function(data) {
      $('#address').html(autocomplete.formatted_address);
      $('#address').html(autocomplete.getPlace().formatted_address);
      $('#summary').html(getSummary(data.meta.counts.total, data.meta.average));
      $('#crime-counts').html('');
      $('#crime-counts').append(function() {
        var str = '';
        $.each(data.meta.counts, function(k, crime) {
          if (k != 'total') {
            str += '<li>' + k + ' - ' + crime + '</li>';
          }
        });
        return str;
      });
      $('.info').slideDown(function() { window.map.panBy([0, $(this).height() / 2]) });
      $.each(markers, function(i, m) { window.map.removeLayer(m) });
      markers = [];
      $.each(data.crimes, function(i, crime) {
        var icon = L.MakiMarkers.icon({icon: 'police', color: '#c0392b', size: 'm'});
        var m = L.marker([crime.lat, crime.lng], {icon: icon});
        m.bindPopup("<b>" + crime.description + "</b><br />" + crime.address + "<br /><small>" + crime.class + "</small><br /><small>" + crime.created_at + "</small>");
        m.addTo(window.map);
        markers.push(m);
      });
    });
  }

  function getSummary(count, average) {
    var percent = (count / average) * 100;
    var start = "The number of serious incidents reported within a quarter mile of this location within the last three months is ";
    if (percent < 50)
      return start + "<span style='color: green; font-weight: strong;'>well below average</span>.";
    if (percent < 100)
      return start + "<span style='color: green; font-weight: strong;'>below average</span>.";
    if (percent > 150)
      return start + "<span style='color: red; font-weight: strong;'>well above average</span>.";
    if (percent > 100)
      return start + "<span style='color: orange; font-weight: strong;'>above average</span>.";
  }
</script>
@stop
