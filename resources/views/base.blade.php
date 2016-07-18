<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <link rel="stylesheet" href="/css/app.css">
    <meta id="token" name="token" value="{{csrf_token()}}">
    @yield('header')
    @yield('othercss' )
</head>
<body>
<div class="scroll-element scroll-y scroll-scrolly_visible">
    <div class="scroll-element_outer">
        <div class="scroll-element_size"></div>
        <div class="scroll-element_track"></div>
        <div class="scroll-bar" style="height: 8px; top: 78.2451px;"></div>
    </div>
</div>
<div class="sky hidden-xs">
    <div class="cloud variant-1"></div>
    <div class="cloud variant-2"></div>
    <div class="cloud variant-3"></div>
    <div class="cloud variant-4"></div>
    <div class="cloud variant-5"></div>
    <div class="cloud variant-6"></div>
    <div class="cloud variant-7"></div>
    <div class="cloud variant-8"></div>

    <div class="cloud variant-9"></div>
    <div class="cloud variant-10"></div>
</div>

@yield('text')
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js'></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
        crossorigin="anonymous"></script>
<script src="/js/app.js"></script>
@yield('otherjs')
</body>
</html>