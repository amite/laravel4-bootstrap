<!DOCTYPE html>

<!--[if IEMobile 7]><html class="no-js iem7 oldie"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html class="no-js ie7 oldie" lang="{{ Config::get('application.language') }}"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html class="no-js ie8 oldie" lang="{{ Config::get('application.language') }}"><![endif]-->
<!--[if (IE 9)&!(IEMobile)]><html class="no-js ie9" lang="{{ Config::get('application.language') }}"><![endif]-->
<!--[if (gt IE 9)|(gt IEMobile 7)]><!--><html class="no-js" lang="{{ Config::get('application.language') }}"><!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=8;FF=3;OtherUA=4" />
    <title>{{ (!empty($head->title) ? $head->title . ' - ' : '') }} @lang('app.app.name')</title>
    <meta name="description" content="{{ (!empty($head->description) ? $head->description : Lang::get('app.app.description')) }}">
    <meta name="author" content="Ellipse Synergie">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//google-analytics.com">
    <link rel="dns-prefetch" href="//www.google-analytics.com">
    <link rel="dns-prefetch" href="//code.jquery.com">

    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" type="text/css">
    
    <!-- Application css -->
	{{ Assets::renderCss() }}
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
      <script src="assets/plugins/bootstrap/plugins/html5shiv.js"></script>
      <script src="assets/plugins/bootstrap/plugins/respond.min.js"></script>
    <![endif]-->
</head>
<body>

	{{ $content }}

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="//code.jquery.com/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/js/console.js"></script>
	
	<!-- Application javascript -->
	{{ Assets::renderJs() }}
	
	@if (App::environment() == 'production')
    <script>
        <!-- Google Analytics -->
    </script>
    @endif
</body>
</html>
