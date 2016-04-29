@extends('layouts.default')
@section('head-stuff')
<link rel="stylesheet" href="css/simple-sidebar.css">
<style>
  #map { position:fixed; top:70px; bottom:0; width:100%; }
  .toast-top-right { margin-top: 70px; }
  #toast-container { z-index: 1048; }
  body {
    overflow-y: hidden;
    margin-top: 70px;
  }
  .filter-panel {
    overflow-y: scroll;
  }
  .overlay{
    text-align: center;
    margin-top: 70px;
    opacity:0.8;
    background-color:#ccc;
    position:fixed;
    width:100%;
    height:100%;
    top:0px;
    left:0px;
    z-index:1029;
  }
  .helper {
    display: inline-block;
    height: 100%;
    vertical-align: middle;
  }

  .loader {
    vertical-align: middle;
    width: 50px;
    height: 50px;
  }

  .map-container {
    margin: 0px;
    padding: 0;
  }

  #wrapper {
    position: fixed;
    top: 70px;
  }

  #sidebar-wrapper {
    background: #fff;
  }

  .sidebar-container {
    margin: 0;
    padding: 0;
  }

  #page-content-wrapper {
    padding: 0;
  }
  .expand-btn {
    z-index: 99999;
    background: #34495e;
    position: fixed;
    top: 50%;
    padding: 5px;
    padding-left: 0;
    border-radius: 0px 30px 30px 0;
  }

  .expand-img {
    transform: rotate(270deg);
  }
</style>
@stop

@section('body')
<div id="wrapper">
  <div class="overlay">
    <span class="helper"></span><img class="loader" src="img/loader.gif">
  </div>
  <div class="filter-panel" id="sidebar-wrapper">
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

<div id="donateModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Hey, freedom ain't free...</h4>
      </div>
      <div class="modal-body">
        <p>And apparently neither is server time. So if you appreciate this
          service, please consider
          <a href='http://ko-fi.com?i=115Z8K8YWIQP' onclick="trackOutboundLink('http://ko-fi.com?i=115Z8K8YWIQP', 'donate')" target='_blank'>donating</a>
          to help keep it alive. Thanks!</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Nah, I'm good</button>
        <a id="donate" href='http://ko-fi.com?i=115Z8K8YWIQP' onclick="trackOutboundLink('http://ko-fi.com?i=115Z8K8YWIQP', 'donate')" target='_blank' class="btn btn-primary">Donate</a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@stop

@section('body-scripts')
<script src="js/leaflet-heat.js"></script>
<script type="text/javascript">
  $(function() {
    if ($(window).width() < 768) {
      $("#wrapper").toggleClass("toggled");
    }
    $('#donateModal').modal();
    $('.filter-panel').height(window.innerHeight - 70);
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

  $('#donate').on('click', function() {
    $('#donateModal').modal('hide');
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
      var heat = data.map(function(el) {
        return [el.lat, el.lng, 1];
      });
      if (window.heatmapLayer) window.map.removeLayer(window.heatmapLayer);
      window.heatmapLayer = L.heatLayer(heat, {radius: 15}).addTo(map);
    }).error(function(data) {
      toastr.error('Something went wrong :(');
    }).complete(function() {
      $('.overlay').hide();
    });
  }
</script>
@stop
