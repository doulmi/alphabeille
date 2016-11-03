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
        body {
            font-size: 34px;
        }
    </style>
</head>
<body>
<div class="container">
    <table class="table">
        <thead class="head">
        <tr>
            <th>昵称</th>
            @foreach($days as $day)
            <th>{{$day}}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($users as $name => $results)
            <tr>
                <td>{{$name}}</td>
                @foreach($results as $result)
                    <td>{{$result['score']}}</td>
                @endforeach
                <td></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>