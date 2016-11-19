<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/video-js.css')}}">
    <title>90天法语背诵群：往期</title>
    <meta name="description" content="90天法语背诵群"/>
    <meta name="Keywords" content="90天法语背诵群">
    <style>
        .list {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container ">
    <h1 class="center">90天法语背诵群</h1>
    <h3 class="center">Alphabeille蜂言法语</h3>
    <center><img src="{{asset('img/168x168.jpg')}}" alt="icon" class="icon"></center>
    <div class="list">
       @foreach($articles as $article)
           <div class="list-item"><a href={{url('articles/' . $article->hash)}}>{{$article->reciteAt}}</a></div>
       @endforeach
    </div>
</div>
</body>
</html>