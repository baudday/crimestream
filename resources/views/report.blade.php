@extends('layouts.default')
@section('head-stuff')
<link rel="stylesheet" href="css/simple-sidebar.css">
<link rel="stylesheet" href="css/reports-styles.css" media="screen" charset="utf-8">
@stop

@section('body')
<div id="wrapper">
  <div class="overlay">
    <span class="helper"></span><img class="loader" src="img/loader.gif">
  </div>
  <div class="info-panel" id="sidebar-wrapper">
    <div class="sidebar-container">
      <div class="container-fluid sidebar-container">
        <div class="row-fluid">
          <div class="col-xs-12">
            <h1>Filters</h1>
            <hr>
            <div class="list-group">
              <a href="#" class="filter list-group-item active" data-slug="accidents">Accidents</a>
              <a href="#" class="filter list-group-item" data-slug="assaults">Assaults</a>
              <a href="#" class="filter list-group-item" data-slug="auto-thefts">Auto Thefts</a>
              <a href="#" class="filter list-group-item" data-slug="burglaries">Burglaries</a>
              <a href="#" class="filter list-group-item" data-slug="burglaries-from-vehicles">Burglaries from Vehicles</a>
              <a href="#" class="filter list-group-item" data-slug="disturbances">Disturbances</a>
              <a href="#" class="filter list-group-item" data-slug="hit-runs">Hit &amp; Runs</a>
              <a href="#" class="filter list-group-item" data-slug="missing-persons">Missing Persons</a>
              <a href="#" class="filter list-group-item" data-slug="shootings">Shootings/Shots Fired/Shots Heard</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="page-content-wrapper">
    <div class="container-fluid map-container">
      <div class="row-fluid">
        <div class="col-xs-12" style="padding-left: 0;">
          <div class="expand-btn">
            <a href="#" id="menu-toggle">
              <img class="expand-img" src="img/expand.png" title="Expand">
            </a>
          </div>
          <div id="map"></div>
        </div>
      </div>
    </div>
  </div>
</div>

@stop

@section('body-scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
<script src="js/leaflet-heat.js"></script>
<script src="js/heatmap.js"></script>
<script src="js/leaflet-heatmap.js"></script>
<script type="text/javascript">
  $(function() {
    if ($(window).width() < 768) {
      $("#wrapper").toggleClass("toggled");
    }
    $('.info-panel').height(window.innerHeight - 70);
    $('#map').height(window.innerHeight - 70);
    drawMap();
    draw();
  });

  $('.filter').on('click', function() {
    if ($(window).width() < 768) {
      $("#wrapper").toggleClass("toggled");
    }
    var slug = $(this).data('slug');
    $('.filter').removeClass('active');
    $(this).addClass('active');
    draw(slug);
    return false;
  });

  $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
  });

  function drawMap() {
    window.map = L.map('map').setView([36.1314, -95.9372], 11);
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
      attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
      minZoom: 10,
      id: 'mapbox.light',
      accessToken: 'pk.eyJ1IjoiYmF1ZGRheSIsImEiOiJBWkFQV2NJIn0.k1sZSEElIyTFmvVLemkZnA'
    }).addTo(window.map);
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
