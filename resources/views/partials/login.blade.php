<form action="/auth/login" method="POST" role="form">
  <legend>Login</legend>

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

  <div class="form-group">
    <label for="email">Username</label>
    <input class="form-control input-lg" type="email" name="email" id="email" tabindex="1" placeholder="ex@mp.le" value="{{ old('email') }}">
  </div>

  <div class="form-group">
    <label for="password">Password</label>
    <input class="form-control input-lg" type="password" name="password" id="password" tabindex="1">
  </div>
  <button type="submit" class="btn btn-default btn-lg" tabindex="3">Login</button>
  or <a href="/auth/register">Register</a>
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
