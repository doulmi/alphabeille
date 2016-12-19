<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <script>
    var token = localStorage.getItem('alpha_token');
    if (!token) {
      window.location.href = '{{url('vocabularies/loginForm')}}'
    }
  </script>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
        integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <link rel="stylesheet" href="{{asset('css/vocabulary.css')}}">

  <title>3个月法语单词群：{{$vocabulary->date}}</title>
  <meta name="description" content="3个月法语单词群:{{$vocabulary->date}}"/>
  <meta name="Keywords" content="3个月法语单词群:{{$vocabulary->date}}">
</head>

<body>
<div class="container ">
  <h1 class="center">3个月法语单词群</h1>
  <h3 class="center">{{$vocabulary->date->format('Y-m-d')}}</h3>
  <h3 class="center">Alphabeille蜂言法语</h3>
  <div id='wx_pic' style='margin:0 auto;display:none;'>
    <img src="{{asset('img/168x168.jpg')}}" alt="icon" class="icon">
  </div>
  <center><img src="{{asset('img/168x168.jpg')}}" alt="icon" class="icon"></center>
  <div class="share-component share-panel hidden-xs hidden-md hidden-sm" data-sites="wechat"
       data-description="@lang('labels.shareTo')">
    @lang('labels.share'):
  </div>

  <div class="row">
    <div class="col-sm-12 col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
      <div class='explication'>
        {!! $vocabulary->parsedContent !!}
      </div>
    </div>
  </div>

  <div class="col-md-6 col-xs-12">
    <a class="btn btn-primary btn-check" id="check-btn">
      打卡
    </a>
  </div>
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script src="{{asset('js/social-share.min.js')}}"></script>
<script src="{{asset('js/audio.js')}}"></script>
<script src="{{asset('js/toastr.min.js')}}"></script>

<script>
  $(function() {
    var btn = $('#check-btn');
    btn.click(function(event) {
      event.preventDefault();
      toastr.options = {
        "positionClass": "toast-top-center"
      };
      $.get("{{url('vocabularies/check')}}" + '/' + localStorage.getItem('alpha_token'), function(response) {
        if(response.success) {
          toastr.success('打卡成功');
        } else {
          toastr.warning('打卡成功');
        }
      });
    });
  });
  //  function togglePlay(id) {
  //    $('#' + id)[0].play();
  //  }
</script>

</body>
</html>