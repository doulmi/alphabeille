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

  <title>3个月法语单词群：{{$vocabulary->date->format('Y-m-d')}}</title>
  <meta name="description" content="3个月法语单词群:{{$vocabulary->date->format('Y-m-d')}}"/>
  <meta name="Keywords" content="3个月法语单词群:{{$vocabulary->date->format('Y-m-d')}}">
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

  <div class="col-md-6 col-md-offset-3 col-xs-12">
    <a class="btn btn-primary btn-check" id="check-btn">
      打卡
    </a>
  </div>

  <div class="word-panel" id="wordPanel">
    <div class="container">
      <div class="clearfix header">
        <button id="closeBtn" class="close white">x</button>
      </div>
      <div style="margin-top: 30px;">
        <span class="word" id="dictWord"></span>
        <span class="prononce" id="dictPrononce">
          <button type="button" class="audio-play-btn" id="audioBtn"><img class="audio-icon" src="data:image/svg+xml;base64,PHN2ZyBmaWxsPSIjMzIyRTMzIiBoZWlnaHQ9IjE4IiB2aWV3Qm94PSIwIDAgMjQgMjQiIHdpZHR oPSIxOCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICAgIDxwYXRoIGQ9Ik 0zIDl2Nmg0bDUgNVY0TDcgOUgzem0xMy41IDNjMC0xLjc3LTEuMDItMy4yOS0yLjUtNC4wM3Y4L jA1YzEuNDgtLjczIDIuNS0yLjI1IDIuNS00LjAyek0xNCAzLjIzdjIuMDZjMi44OS44NiA1IDMu NTQgNSA2Ljcxcy0yLjExIDUuODUtNSA2LjcxdjIuMDZjNC4wMS0uOTEgNy00LjQ5IDctOC43N3M tMi45OS03Ljg2LTctOC43N3oiLz4KICAgIDxwYXRoIGQ9Ik0wIDBoMjR2MjRIMHoiIGZpbGw9Im5vbmUiLz4KPC9zdmc+"/></button>
        </span>
      </div>
      <div class="explication" id="dictEx">
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script src="{{asset('js/social-share.min.js')}}"></script>
<script src="{{asset('js/audio.js')}}"></script>
<script src="{{asset('js/toastr.min.js')}}"></script>

<script>
  $(function () {
    var btn = $('#check-btn');
    btn.click(function (event) {
      event.preventDefault();
      toastr.options = {
        "positionClass": "toast-top-center"
      };
      $.get("{{url('vocabularies/check')}}" + '/' + localStorage.getItem('alpha_token'), function (response) {
        if (response.success) {
          toastr.success('打卡成功');
        } else {
          toastr.warning('打卡成功');
        }
      });
    });

    var closeBtn = '<button type="button" id="close" class="close" onclick="$(\'.popver\').popover(\'hide\');">&times;</button>';

    var audio = $('<audio/>', {
      controls: 'controls'
    });

    var wordPanel = $('#wordPanel');
    var togglePanel = function () {
      wordPanel.hide();
    };

    $('#closeBtn').click(togglePanel);
    var audioBtn = $('#audioBtn');

    audioBtn.click(function () {
      audio.appendTo('body');
      audio.trigger('play');
    });

    var dictEx = $('#dictEx');
    var dictWord = $('#dictWord');
    var dictPrononce = $('#dictPrononce');

    $(".explication span").click(function () {
      wordPanel.show();
      var word = $(this).html();
      dictWord.html('');
      audioBtn.hide();
      dictEx.html('加载中...');
      $.get('{{url('/api/words')}}' + '/' + word, function (response) {
        var src = response['audio'];
        if(src == '') {
          audioBtn.hide();
        } else {
          audioBtn.show();
          audio = $('<audio/>', { });
          $('<source/>').attr('src', src).appendTo(audio);
        }

        dictWord.html(word);
        dictEx.html(response['msg']);
      });
    });
  });
</script>

</body>
</html>