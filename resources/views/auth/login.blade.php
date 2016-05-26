@extends('layouts.default')

@section('body')
<div class="container" style="margin-top: 100px;">
  <div class="row">
    <div class="col-sm-6 col-sm-offset-3">
      <div class="panel panel-default">
        <div class="panel-body">
          @include('partials.login')
        </div>
      </div>
    </div>
  </div>
</div>
@stop
