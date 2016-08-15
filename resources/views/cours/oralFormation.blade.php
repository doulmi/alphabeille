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
                        <source src="http://o9dnc9u2v.bkt.clouddn.com/audios/2700.mp3">
                    </audio>
                </span>
                <span class="db audio_info_area">
                    <strong class="db audio_title">1v1 3个月法语口语集训</strong>
                </span>
                <span class="audio_length tips_global pull-right">2:02</span>
                <span id="voice_progress" class="progress_bar" style="width: 0%;"></span>
            </div>

            <div id="preview-contents" style="padding-left: 35px; padding-right: 35px; padding-bottom: 375px;">
                <div id="wmd-preview" class="preview-content"></div>
                <div id="wmd-preview-section-1" class="wmd-preview-section preview-content">

                </div>
                <div id="wmd-preview-section-15" class="wmd-preview-section preview-content">

                    <h1 id="1v1-3个月法语口语集训">1v1 3个月法语口语集训</h1>

                    <p>你背了5000个单词? 学了所有的语态和语式? 可你说不出来 ?!!!</p>

                    <p>
                        口语就像一个人的外貌，一口漂亮的口语，总是能特别引人注意。或许你只是刚学，苦于无法锻炼自己的口语，或者你已经学习法语多年，却还是学的一口哑巴法语，甚至有时候连说的勇气也没有。Alphabeille法语1V1口语集训，是一对一集中口语训练课程，由资深留法老师和法籍外教联合为你授课，不仅让你短期内在法语口语上得到突破，更培养你和法国人对话的习惯。
                        <br>
                        无论你是法语专业的学生，还是你自学法语，甚至你打算留学法国或者准备面签，只要你想突破口语，这里就是你最好的选择。Alphabeille法语1v1口语集训，只要2700，每周3节课，坚持3个月，让你的法语捉住别人的耳朵
                        !</p>

                </div>
                <div id="wmd-preview-section-31" class="wmd-preview-section preview-content">

                    <h2 id="每周课程内容包括">每周课程内容包括 :</h2>

                    <ul>
                        <li>一节语音练习课(纠正发音，连读，联诵，省音，语调等等)</li>
                        <li>一节口语表达课 (法语实用口语句型词汇，常见俗语，俚语等等)</li>
                        <li>一节外教练习课(与外教零距离口语实战，当堂点评，让你高效学习)</li>
                    </ul>

                    <p>Alphabeille法语3个月 1v1 口语集训，一对一课程，每周3节课，一节60分钟，现在+微信Alphabeillestudio即可报名 ! </p>

                    <center>
                        <p>扫码加我们微信助教</p>

                        <p><img src="http://o9dnc9u2v.bkt.clouddn.com/images/qr-alphabeilleStudio.png"
                                alt="enter image description here" title=""></p>

                    </center>
                </div>
                <div id="wmd-preview-section-footnotes" class="preview-content"></div>
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