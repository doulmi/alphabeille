<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>三个月后法语单词群</title>
  <style>
    a {
      text-decoration: none;
      color: #212121;
    }

    .center {
      text-align: center;
      padding: 20px 10px;
      border-bottom: 1px solid lightgrey;
    }
  </style>
</head>
<body>
<div class="container">
  <div >
    @foreach($vocabularies as $vocabulary)
      <div class="center"><a href="{{asset('vocabularies/' . $vocabulary->hash)}}">{{ $vocabulary->date->format('Y-m-d') }}</a></div>
    @endforeach
  </div>
</div>
</body>
</html>