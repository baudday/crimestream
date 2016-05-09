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
            <li><a href="/">Home</a></li>
            <li><a href="report">Reports</a></li>
            <li><a href="about">About</a></li>
            <li><a target="_blank" onclick="trackOutboundLink('https://citygram.org/tulsa', 'alerts-btn')" href="//citygram.org/tulsa">Text Message Alerts!</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            @if (Auth::check())
            <li><a href="#">{{ Auth::user()->email }}</a></li>
            @else
            <li><a id="login" href="#" data-toggle="modal" data-target="#loginModal">Login</a></li>
            @endif
          </ul>
        </div>
      </div>
    </nav>
    @if (!isset($hide['twitter']))
    <div id="follow-btn-container" style='position: absolute; top: 80px; right: 10px; z-index: 1048;'>
      <a href="https://twitter.com/CrimeStreamBot" onclick="trackOutboundLink('https://twitter.com/CrimeStreamBot', 'twitter-follow-btn')" class="twitter-follow-button" data-show-count="false" data-size="large" data-show-screen-name="false">Follow @CrimeStreamBot</a>
    </div>
    @endif
    @if (!isset($hide['donate']))
    <a href='http://ko-fi.com?i=115Z8K8YWIQP' onclick="trackOutboundLink('http://ko-fi.com?i=115Z8K8YWIQP', 'coffee')" target='_blank'><img style='position: absolute; bottom: 20px; left: 0; border: 0; z-index: 9999; width: 130px;' src='https://az743702.vo.msecnd.net/cdn/btn3.png' border='0' alt='Buy me a coffee at ko-fi.com' /></a>
    @endif
    @yield('body')
    @if (!Auth::check())
    <div id="loginModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Login</h4>
          </div>
          <div class="modal-body">
            <div class="row" style="margin-bottom: 10px;">
              <div class="col-sm-6 col-sm-offset-3">
                <a class="btn btn-lg btn-block btn-social btn-facebook">
                  <span class="fa fa-facebook"></span> Login with Facebook
                </a>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6 col-sm-offset-3">
                <a class="btn btn-lg btn-block btn-social btn-twitter">
                  <span class="fa fa-twitter"></span> Login with Twitter
                </a>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    @endif
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    @yield('body-scripts')
  </body>
</html>
