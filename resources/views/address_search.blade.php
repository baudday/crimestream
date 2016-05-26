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
<script src="js/address-lookup.js"></script>
@stop
