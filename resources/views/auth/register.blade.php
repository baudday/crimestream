@extends('layouts.default')

@section('body')
<div class="container" style="margin-top: 100px;">
  <div class="row">
    <div class="col-sm-6 col-sm-offset-3">
      <div class="panel panel-default">
        <div class="panel-body">
          @if (session('trial_msg'))
          <div class="alert alert-success"><strong>{{ session('trial_msg') }}</strong></div>
          @endif
          <form method="post" action="/auth/register">
            <legend>Register</legend>

            @if (count($errors) > 0)
              <div class="alert alert-danger">
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            {!! csrf_field() !!}

            <div class='form-group'>
              <label for='name'>Name</label>
              <input name="name" id="name" type='text' class='form-control input-lg' value="{{ old('name') }}" placeholder='John Smith' tabindex="1">
            </div>

            <div class='form-group'>
              <label for='email'>Email</label>
              <input name="email" id="email" type='email' class='form-control input-lg' value="{{ old('email') }}" placeholder='ex@mp.le' tabindex="1">
            </div>

            <div class='form-group'>
              <label for='password'>Password</label>
              <input name="password" id="password" type='password' class='form-control input-lg' tabindex="1">
            </div>

            <div class='form-group'>
              <label for='password_confirmation'>Confirm Password</label>
              <input name="password_confirmation" id="password_confirmation" type='password' class='form-control input-lg' tabindex="1">
            </div>

            <button type='submit' class='btn btn-lg btn-default'>Register</button>
          </form>

          <hr>

          <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
              <a href="/auth/twitter" class="btn btn-lg btn-block btn-social btn-twitter">
                <span class="fa fa-twitter"></span> Login with Twitter
              </a>
            </div>
          </div>
          <div class="row" style="margin-top: 10px;">
            <div class="col-sm-8 col-sm-offset-2">
              <a href="/auth/facebook" class="btn btn-lg btn-block btn-social btn-facebook">
                <span class="fa fa-facebook"></span> Login with Facebook
              </a>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
