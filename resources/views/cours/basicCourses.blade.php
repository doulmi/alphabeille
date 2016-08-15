@extends('app')

@section('title')@lang('labels.menus.title')@endsection

@section('header')
    <meta name="description" content="@lang('labels.basicCourses.description')">
    <meta name="Keywords" content="@lang('labels.menus.keywords')">
    <style>
        .audio_wrp {
            border: 1px solid #ebebeb;
            background-color: #fcfcfc;
            overflow: hidden;
            padding: 20px 20px 20px 20px;
        }

        .pic_audio_default {
            width: 18px;
        }

        .audio_length {
            font-size: 14px;
            margin-top: 4px;
            margin-left: 1em;
            float: right;
        }

        .audio_title {
            font-weight: 400;
            font-size: 17px;
            margin-top: -2px;
            margin-left: 20px;
            margin-bottom: -3px;
            width: auto;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            word-wrap: normal;
        }

        .audio_area .audio_source {
            display: block;
            font-size: 14px;
        }

        .tips_global {
            color: #8c8c8c;
        }

        .audio_info_area {
            overflow: hidden;
        }

        .progress_bar {
            position: absolute;
            left: 0;
            bottom: 0;
            background-color: #0cbb08;
            height: 2px;
        }
    </style>
@endsection

@section('content')
    <div class="morning">
        <div class="margin-top"></div>
        <div class="Header"></div>
        <div class="Card-Collection">
            <div class="audio_wrp">
                <span class="audio_play_area">
                    <i class="icon_audio_default"></i>
                    <i class="icon_audio_playing"></i>
                    <a onclick="play()"><img
                                src="http://res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/audio/icon_audio_unread26f1f1.png"
                                alt="" class="pic_audio_default"></a>
                    <audio id="audio" preload="auto" controls="" style="width: 0px; height: 0px; visibility: hidden;">
                        <source src="http://o9dnc9u2v.bkt.clouddn.com/audios/699-1.mp3">
                    </audio>
                </span>
                <span class="db audio_info_area">
                    <strong class="db audio_title">Alphabeille法语， 699两个月带你法语入门 !</strong>
                </span>
                <span class="audio_length tips_global pull-right">2:19</span>
                <span id="voice_progress" class="progress_bar" style="width: 0%;"></span>
            </div>

            <div id="preview-contents" style="padding-bottom: 75px;">
                <div id="wmd-preview" class="preview-content"></div>
                <div id="wmd-preview-section-4711" class="wmd-preview-section preview-content">

                </div>
                <div id="wmd-preview-section-4744" class="wmd-preview-section preview-content">

                    <h1 id="alphabeille法语-699两个月带你法语入门">Alphabeille法语， 699两个月带你法语入门 !</h1>

                    <p>
                        30分钟，你可以做许多事情，看半集综艺节目，听8首歌，聊一会天，甚至发半个小时的呆。但你也可以选择去学习一门美丽的语言，法语。每天30分钟，开启你的第“三”语言，充实你自己的人生的简历。只要699元，两个月我们带你法语入门，我们让你少走自学的弯路，让你的法语学习，从一口纯正的发音开始
                        ! </p>

                    <p>Alphabeille法语推出两个月法语入门!</p>

                </div>
                <div id="wmd-preview-section-4745" class="wmd-preview-section preview-content">

                    <h2 id="课程内容包括">课程内容包括 :</h2>

                    <ul>
                        <li>基础发音规则</li>
                        <li>发音练习</li>
                        <li>基础对话</li>
                        <li>法国文化介绍</li>
                        <li>其他</li>
                    </ul>

                    <p>
                        小班微信授课，每周三节课，一节30分钟。主讲老师巴黎三大翻译专业毕业，口语流利，已留法长达六年，熟知法语教学和法国文化，更有法籍外教定期亲自帮你纠正发音，让你学一口纯正法语，现在+微信Alphabeillestudio即可报名
                        !</p>

                </div>
                <div id="wmd-preview-section-footnotes" class="preview-content"></div>
                <center><p>扫码加我们微信助教</p>
                    <p>
                        <img src="http://o9dnc9u2v.bkt.clouddn.com/images/qr-alphabeilleStudio.png"
                             alt="助教QR" title="" style="width:300px">
                </center>
                </p>
            </div>
        </div>
    </div>
@endsection

@section('otherjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>
    <script>
        $('img.Card-image').lazyload();
        var audio = $('#audio');
        function play() {
            if (audio.prop('paused')) {
                audio.trigger("play");
            } else {
                audio.trigger('pause');
            }
        }
    </script>

@endsection