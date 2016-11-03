<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/video-js.css')}}">
    <title>90天法语背诵群：{{$article->reciteAt}}</title>
    <meta name="description" content="90天法语背诵群:{{$article->reciteAt}}"/>
    <meta name="Keywords" content="90天法语背诵群:{{$article->reciteAt}}">
    <script type="text/javascript">
        //        // 对浏览器的UserAgent进行正则匹配，不含有微信独有标识的则为其他浏览器
        //        var useragent = navigator.userAgent;
        //        if (useragent.match(/MicroMessenger/i) != 'MicroMessenger') {
        //            // 这里警告框会阻塞当前页面继续加载
        //            alert('已禁止本次访问：您必须使用微信内置浏览器访问本页面！');
        //            // 以下代码是用javascript强行关闭当前页面
        //            var opened = window.open('about:blank', '_self');
        //            opened.opener = null;
        //            opened.close();
        //        }
    </script>
</head>
<body>
<div class="container ">
    <h1 class="center">90天法语背诵群</h1>
    <h3 class="center">{{$article->reciteAt}}</h3>
    <h3 class="center">Alphabeille蜂言法语</h3>
    <div id='wx_pic' style='margin:0 auto;display:none;'>
        <img src="{{asset('img/168x168.jpg')}}" alt="icon" class="icon">
    </div>
    <center><img src="{{asset('img/168x168.jpg')}}" alt="icon" class="icon"></center>
    <div class="share-component share-panel hidden-xs hidden-md hidden-sm" data-sites="wechat"
         data-description="@lang('labels.shareTo')" >
        @lang('labels.share'):
    </div>

    <div class="row">
        <div class="col-sm-12 col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
            @if($article->type == 'video')
                <video id="my_video" class="video-js vjs-default-skin" controls preload
                       data-setup='{ "aspectRatio":"1920:1080", "playbackRates": [0.5, 0.75, 1, 1.25] }'>
                    <source src="{{$article->url}}" type='video/mp4'>
                </video>
            @else
                <div class="playerPanel">
                    <audio id='audio' preload="auto" controls hidden>
                        <source src="{{$article->url}}"/>
                    </audio>
                </div>
            @endif

            <div class='explication'>
                {!! $article->parsedContent !!}

                <div class="Row-1"></div>
                1. 务必有效练习<strong>50</strong>次以上再提交，草率提交三次则被视为缺席一天<br/>
                2. 每人每天最多只能发送三次语音，前两次可以撤销，累计超过三次则不予点评<br/>
                3. 每天作业提交时间为自己所在时区的<strong>21:30之前</strong>，之后提交的则视为缺席<br/>
            </div>

            <div class="Row-3"></div>

        </div>
    </div>
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script src="{{asset('js/social-share.min.js')}}"></script>
@if($article->type == 'audio')
    <script src="{{asset('js/audio.min.js')}}"></script>
@else
    <script src="{{asset('js/video.min.js')}}"></script>
    <script>
        videojs("my_video").ready(function () {
            $('.video-js').show();
        });
    </script>
@endif

</body>
</html>