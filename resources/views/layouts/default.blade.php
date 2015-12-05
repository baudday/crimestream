<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CrimeStream</title>
    <link href='/css/app.css' rel='stylesheet'>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/leaflet.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src='//code.jquery.com/jquery-2.1.4.min.js'></script>
    <script src='//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js'></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/leaflet.js"></script>
    <script src='js/Leaflet.MakiMarkers.js'></script>
    <script src='//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js'></script>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-71019703-1', 'auto');
      ga('send', 'pageview');

    </script>
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
          <a class="navbar-brand" href="#">CrimeStream</a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a target="_blank" href="https://www.tulsapolice.org/live-calls-/police-calls-near-you.aspx" onclick="trackOutboundLink('https://www.tulsapolice.org/live-calls-/police-calls-near-you.aspx', 'live-calls')">TPD Live Calls</a></li>
            <li role="separator" class="divider"></li>
            <li><a target="_blank" href="https://twitter.com/@willem_jr" onclick="trackOutboundLink('https://twitter.com/@willem_jr', 'twitter')"><img src="/img/twitter.png" width="12"> @willem_jr</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <a href="https://github.com/baudday/crimestream" onclick="trackOutboundLink('https://github.com/baudday/crimestream', 'github')" target="_blank"><img style="position: absolute; top: 0; left: 0; border: 0; z-index: 9999;" src="https://camo.githubusercontent.com/c6625ac1f3ee0a12250227cf83ce904423abf351/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f6c6566745f677261795f3664366436642e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_left_gray_6d6d6d.png"></a>
    <a href='http://ko-fi.com?i=115Z8K8YWIQP' onclick="trackOutboundLink('http://ko-fi.com?i=115Z8K8YWIQP', 'coffee')" target='_blank'><img style='position: absolute; bottom: 20px; left: 0; border: 0; z-index: 9999; width: 130px;' src='https://az743702.vo.msecnd.net/cdn/btn3.png' border='0' alt='Buy me a coffee at ko-fi.com' /></a>
    @yield('body')
    @yield('body-scripts')
  </body>
</html>
