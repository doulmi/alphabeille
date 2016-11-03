<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <title></title>
    <style>
        .block {
            font-size: 40px;
            margin: 60px 0;
        }

        .comment {
            margin-top: 10px;
        }

        .star {
            width: 50px;
        }
    </style>
</head>
<body>
<div class="container">
    @foreach($users as $user)
        <div class="block">
            <div class="title">@ {{$user['name']}}</div>
            <div class="note">
                @for($i = 0; $i < intval($user['score']); $i ++ )
                    <img src="{{asset('img/star-on.png')}}" class="star">
                @endfor

                @if(intval($user['score'] * 2) % 2 != 0)
                    <img src="{{asset('img/star-half.png')}}" class="star">
                @endif
            </div>
            <div class="comment">评语: {{$user['comment']}}</div>
        </div>
    @endforeach
</div>
</body>
</html>