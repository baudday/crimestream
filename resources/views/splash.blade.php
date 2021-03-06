@extends('layouts.default')

@section('head-stuff')
<link rel="stylesheet" href="css/splash.css" charset="utf-8">
@stop

@section('body')
<div class="bg-img"></div>
<div class="vcenter">
  <div class="container body">
    <div class="row">
      <div class="col-sm-8">
        <div class="jumbotron" style="text-align: center; margin-bottom: 0; padding-top:0; padding-bottom: 0;">
            <h1 style="font-family: Bangers;">CrimeStream Plus</h1>
            <p>Get insight into crime in Tulsa for only $5 a month.</p>
            <p><a class="btn btn-info btn-lg" href="/trial" role="button">Try Free for 7 Days</a></p>
            <p><small><a href="/map">Take me to the Map</a></small></p>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="row">
          <div class="col-sm-12">
            <h1><img height="50" src="/img/search.png" /> Address Search</h1>
            <hr>
            <p>
              Enter an address and we'll give you a breakdown of
              serious police calls near that location within the last 3 months.
              You'll also see how it compares to the rest of Tulsa.
            </p>
          </div>
          <div class="col-sm-12">
            <h1><img height="50" src="/img/heat.png" /> Heatmaps</h1>
            <hr>
            <p>
              Analyze heatmaps of calls by category and see where the
              hotspots are for everything from disturbances to burglaries.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop

@section('body-scripts')
<script type="text/javascript">
  $(function() {
    var img = $('.bg-img');
    var downloadingImg = new Image();

    downloadingImg.src = '/img/bg.jpg';

    downloadingImg.onload = function() {
      img.css({
        'background-image': 'url("' + this.src + '")',
        '-webkit-filter': 'blur(0px)',
        '-moz-filter': 'blur(0px)',
        '-o-filter': 'blur(0px)',
        '-ms-filter': 'blur(0px)',
        'filter': 'blur(0px)'
      });
    }
  });
</script>
@stop
