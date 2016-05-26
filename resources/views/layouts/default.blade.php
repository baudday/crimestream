<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#34495e">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#34495e">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="#34495e">
    <title>CrimeStream</title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/leaflet.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link href='/css/app.css' rel='stylesheet'>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css" media="screen" charset="utf-8">
    @if (!Auth::check())
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.0.0/bootstrap-social.min.css" media="screen" charset="utf-8">
    @endif
    <script src='//code.jquery.com/jquery-2.1.4.min.js'></script>
    <script src='//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js'></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/leaflet.js"></script>
    <script src='js/Leaflet.MakiMarkers.js'></script>
    <script src='//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js'></script>
    @if (getenv('APP_ENV') == 'production')
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-71019703-1', 'auto');
      ga('send', 'pageview');

    </script>
    @endif
    <script>
      var trackOutboundLink = function(url, name) {
        if (!name) name = "outbound";
        ga('send', 'event', name, 'click', url);
      }
    </script>
    @yield('head-stuff')
  </head>
  <body>
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">CrimeStream</a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
          <ul class="nav navbar-nav">
            @if (!\Auth::check() || !\Auth::user()->subscribed('main'))
            <li><a href="/">Home</a></li>
            @endif
            <li><a href="/map">Map</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reporting <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="/address-search">Address Search</a></li>
                <li><a href="/heatmaps">Heatmaps</a></li>
              </ul>
            </li>
            <li><a target="_blank" onclick="trackOutboundLink('https://citygram.org/tulsa', 'alerts-btn')" href="//citygram.org/tulsa">SMS Alerts</a></li>
            <li><a href="/about">About</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            @if (Auth::check())
            <li><a href="/account"><img height="20" src="{{ Auth::user()->avatar }}" class="img-circle"> Account</a></li>
            @else
            <li><a id="login" href="#" data-toggle="modal" data-target="#loginModal">Login</a></li>
            @endif
          </ul>
        </div>
      </div>
    </nav>
    @yield('body')
    @if (!Auth::check())
    <div id="loginModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body">
            @include('partials.login')
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    @endif
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    @yield('body-scripts')
  </body>
</html>
