@extends('layouts.default')

@section('head-stuff')
<style>
  body { margin-top: 70px; }
</style>
@stop

@section('body')
<div class="container" style="margin-top: 60px;">
  <div class="row">
    <div class="col-sm-8 col-sm-offset-2">
      <h3>
        <img class="img-circle" src="{{ $user->avatar }}" /> My Account
        <span class="pull-right">
          <a href="/auth/logout" class="btn btn-danger">Logout</a>
        </span>
      </h3>
      <hr>
      @if (session('update_success'))
      <div class="alert alert-success">{{ session('update_success') }}</div>
      @endif
      @if (session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
      @endif
      @if (count($errors) > 0)
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
    </div>
    <div class="col-sm-8 col-sm-offset-2">
      <h4>General</h4>
      <form action="/user/{{ $user->id }}" method="post">
        {!! csrf_field() !!}
        {!! method_field('put') !!}
        <div class='form-group'>
          <label for="name">Name</label>
          <input name="name" id="name" type='text' class='form-control input-lg' placeholder='Name' value="{{ $user->name }}" tabindex="1">
        </div>
        <div class='form-group'>
          <label for="email">Email</label>
          <input name="email" id="email" type='text' class='form-control input-lg' placeholder='Email' value="{{ $user->email }}" tabindex="1">
          <small><i>Changing this will change your login email!</i></small>
        </div>
        <button type='submit' class='btn btn-lg btn-default' tabindex="2">Update</button>
      </form>

      <hr>
      <h4>Subscription</h4>
        @if ($user->subscribed('main'))
          @if ($user->subscription('main')->onTrial())
          <h4><span class="label label-warning">Trial ends {{ date('M d, Y', strtotime($user->subscription('main')->trial_ends_at)) }}</span></h4>
            @if ($user->subscription('main')->cancelled())
            <a href="/subscription/resume" class="btn btn-success">Resume Subscription</a>
            @else
            <a href="/subscription/cancel" class="btn btn-danger">Cancel Subscription</a>
            @endif

          @elseif ($user->subscription('main')->onGracePeriod())
          <h4><span class="label label-warning">Active until {{ date('M d, Y', strtotime($user->subscription('main')->ends_at)) }}</span></h4>
          <a href="/subscription/resume" class="btn btn-success">Resume Subscription</a>

          @else
          <h4><span class="label label-success">Active</span></h4>
          <a href="/subscription/cancel" class="btn btn-danger">Cancel Subscription</a>
          @endif

        @else
        <h4><span class="label label-default">Unsubscribed</span></h4>
        @endif

      <hr>

      <h4>Payment</h4>
      <form id="payment-form" action="@if ($user->subscribed('main'))/subscription/update @else/subscription/create @endif" method="post">
        {!! csrf_field() !!}
        @if ($user->subscribed('main'))
        {!! method_field('put') !!}
        @endif
        <div class="alert alert-danger payment-errors" style="display: none;"></div>
        <div class="row form-group">
          <div class="col-sm-8">
            <label for="number">Credit Card Number</label>
            <input name="number" id="number" data-stripe="number" type='text' class='form-control input-lg' placeholder='**** **** **** {{ $user->card_last_four ?: "1234" }}' tabindex="3">
          </div>
          <div class="col-sm-4">
            <label for="number">CVV</label>
            <input name="cvv" id="cvv" data-stripe="cvv" type='text' class='form-control input-lg' tabindex="3">
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12">
            <label>Expiration Date</label>
          </div>
        </div>
        <div class="row form-group">
          <div class="col-xs-6">
            <select class="form-control input-lg" name="exp_month" data-stripe="exp_month" tabindex="3">
            	<option value="01">January</option>
            	<option value="02">February</option>
            	<option value="03">March</option>
            	<option value="04">April</option>
            	<option value="05">May</option>
            	<option value="06">June</option>
            	<option value="07">July</option>
            	<option value="08">August</option>
            	<option value="09">September</option>
            	<option value="10">October</option>
            	<option value="11">November</option>
            	<option value="12">December</option>
            </select>
          </div>
          <div class="col-xs-6">
            <select class="form-control input-lg" name="exp_year" data-stripe="exp_year" tabindex="3">
              @for ($i = 2016; $i < 2027; $i++)
              <option value="{{ $i }}">{{ $i }}</option>
              @endfor
            </select>
          </div>
        </div>
        <div class="row form-group">
          <div class="col-xs-12">
            <img src="img/stripe.png"> <small><i>We will never touch your credit card information</i></small>
          </div>
        </div>
        <div class="row form-group">
          <div class="col-xs-12">
          @if ($user->subscribed('main'))
          <button tabindex="4" type="submit" class="btn btn-lg btn-default submit">Update Card</button>
          @else
          <button tabindex="4" type="submit" class="btn btn-lg btn-success submit">Subscribe</button>
          @endif
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@stop

@section('body-scripts')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
  Stripe.setPublishableKey('{{ env("STRIPE_API_KEY") }}');
</script>
<script type="text/javascript" src="/js/subscriptions.js"></script>
@stop
