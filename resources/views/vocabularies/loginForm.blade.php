<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
        integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <link rel="stylesheet" href="{{asset('css/video-js.css')}}">
  <style>
    .full-width {
      width: 100%;
    }

    .hide {
      display: none;
    }

    .error {
      color: red;
    }
  </style>
</head>
<body>
<div class="container">
  <div id='wx_pic' style='margin:0 auto;display:none;'>
    <img src="{{asset('img/168x168.jpg')}}" alt="icon" class="icon">
  </div>
  <center><img src="{{asset('img/168x168.jpg')}}" alt="icon" class="icon"></center>
  <div class="form-group">
    <label for="student">学号:</label>
    <input type="number" class="form-control" id="student" name="student">
    <label class="hide" id="error">学号还是空的哟</label>
  </div>
  <div class="form-group">
    <button class="btn btn-primary full-width" onclick="login()">登录</button>
  </div>
</div>
<script>
  function login() {
    var token = document.querySelector('#student');
    if (token.value.trim() == '') {
      document.querySelector('#error').className = 'error';
    } else {
      document.querySelector('#error').className = 'hide';
      localStorage.setItem('alpha_token', token.value);
      window.location.href = "{{url('vocabularies')}}"
    }
  }
</script>
</body>
</html>