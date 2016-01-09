@extends('layouts.default')

@section('head-stuff')
<style>
  body { margin-top: 70px; }
  .header-bg {
    background: url('img/about_header.png') no-repeat center center;
  }
  .header-container {
    height: 150px;
  }
</style>
@stop

@section('body')
<div class="container-fluid header-bg">
  <div class="row-fluid">
    <div class="col-xs-12 header-container"></div>
  </div>
</div>
<div class="container">
  <div class="row">
    <div class="col-sm-8 col-sm-offset-2">
      <h1>About</h1>
      <hr>
      <p>CrimeStream was created by <a onclick="trackOutboundLink('http://ko-fi.com?i=115Z8K8YWIQP', 'willem')" href="http://twitter.com/willem_jr" target="_blank">Willem Ellis</a>
         as a way to map live police calls from the
         <a onclick="trackOutboundLink('http://ko-fi.com?i=115Z8K8YWIQP', 'live-calls')" href="https://www.tulsapolice.org/live-calls-/police-calls-near-you.aspx" target="_blank">TPD Live Calls</a>
         page. After being featured on
         <a onclick="trackOutboundLink('http://ko-fi.com?i=115Z8K8YWIQP', 'kjrh')" href="http://www.kjrh.com/news/local-news/developer-creates-new-way-to-track-crime-in-tulsa" target="_blank">KJRH</a>,
         the idea gained traction and many Tulsans have been using CrimeStream
         daily to see what's happening around town. The addition of the
         <a onclick="trackOutboundLink('http://ko-fi.com?i=115Z8K8YWIQP', 'twitter')" href="http://twitter.com/CrimeStreamBot" target="_blank">CrimeStreamBot</a>
         Twitter account allows users to monitor serious events such as wanted
         subjects, missing persons, burglaries and more, around town. Finally,
         reporting gives users insight into trends based on historic data since
         CrimeStream's launch.
       </p>
       <p>We plan to add more functionality such as location-based alerts and
         and possibly a mobile app. This is an open source project and
         contributions are encouraged. Please check out the
         <a onclick="trackOutboundLink('http://ko-fi.com?i=115Z8K8YWIQP', 'github')" href="http://github.com/baudday/crimestream" target="_blank">Github repo</a>
         if you are interested in contributing.
       </p>
       <hr>
       <p>CrimeStream is a privately-funded project. If you appreciate this
         service, please consider making a small
         <a href='http://ko-fi.com?i=115Z8K8YWIQP' onclick="trackOutboundLink('http://ko-fi.com?i=115Z8K8YWIQP', 'donate')" target='_blank'>donation</a>.
       </p>
    </div>
  </div>
</div>
@stop
