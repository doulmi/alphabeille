<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="Keywords" content="Alphabeille">
    <title>@yield('title')</title>
    <link href='https://fonts.googleapis.com/css?family=Lato:400,300,400italic,700,900' rel='stylesheet'
          type='text/css'>
    {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"--}}
          {{--integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">--}}
    {{--<link rel="stylesheet" href="/css/dropdowns-enhancement.min.css">--}}
    <link rel="stylesheet" href="/css/app.css">
    {{--<link rel="stylesheet" href="/css/tooltips.css">--}}
    <meta id="token" name="token" value="{{csrf_token()}}">
    @yield('othercss' )
</head>
<body >
<div class="sky">
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
    <div class="cloud variant-11"></div>
</div>

    @yield('text')
<script src="/js/app.js"></script>
@yield('otherjs')
</body>
</html>