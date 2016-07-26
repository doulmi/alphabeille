@extends('app')

@section('title')
    @lang('labels.index.title')
@endsection

@section('header')
    <meta name="description" content="@lang('labels.index.description')">
    <meta name="Keywords" content="@lang('labels.index.keywords')">
    <meta name="google-site-verification" content="p4OOrrT_9YZ-IQLDF02ChLQuNHEW7xodyKVeZoe8FU8"/>
    <meta name="baidu-site-verification" content="4Y6Akg4Bz5"/>
    <link href="http://vjs.zencdn.net/5.10.7/video-js.css" rel="stylesheet">
    <style>
        /* Show the controls (hidden at the start by default) */
        .video-js .vjs-control-bar {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
        }


        .video-js .vjs-play-progress:before {
            top: -0.633333333333333em;
        }

        .vjs-slider-horizontal .vjs-volume-level:before {
            top: -0.633em;
        }

        .video-js .vjs-control:before {
            top: 3px;
        }

        .video-js .vjs-time-control {
            top: 8px;
        }

        .vjs-playback-rate .vjs-playback-rate-value {
            top : 3px;
        }

        #my_video {

            display: none;
        }

        .vjs-default-skin .vjs-big-play-button, .vjs-default-skin.vjs-big-play-centered .vjs-big-play-button {
            left: 50%;
            margin-left: -1.5em;
            top: 50%;
            margin-top: -1.1em;
        }

    </style>
@endsection

@section('content')
    <div class="body">
        <div class="night fullscreen">
            <div class="jumbotron ad-jumbotron">
                <div class="Header"></div>
                <div class="container">
                    <div class="col-md-6 top-80 ">
                        <h1 class="BlockMessage BlockMessage-With-Spacing margin-top-80">@lang('labels.learnIntroduction')</h1>
                        <form action="{{url('search')}}" role="search" id="searchForm" method="get">
                            <div class="form-group">
                                <div class="input-group navbar-search-index">
                                    <input type="text" class="search-input form-control" name="keys" id="keys"
                                           aria-label="" placeholder="@lang('labels.whatuwant')"
                                           value="{{Request::get('keys') }}">
                    <span onclick="search()" class="input-group-addon search-btn"><span
                                class="glyphicon glyphicon-search "></span></span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 video-panel">
                        <div class="top-48"></div>
                        <video id="my_video" class="video-js vjs-default-skin"
                               controls preload data-setup='{ "aspectRatio":"1920:1080" }' data-setup='{"language":"fr"}'>
                            <source src="http://o9dnc9u2v.bkt.clouddn.com/videos/Alphabeille_1080P.mp4" type='video/mp4'>
                        </video>
                    </div>
                </div>
            </div>

            <div class="Header"></div>
            <div class="Card-Collection row">
                <h2 class="Heading-Fancy row">
                    <span class='title'>@lang('labels.minitalks')</span>
                </h2>
                @include('minitalks.minitalksList')
                <a class="btn btn-default more" href="{{url('/minitalks')}}">@lang('labels.more')</a>
            </div>
        </div>

        <div class="night-fall fullscreen">
            <div class="Header"></div>

            <div class="Card-Collection row">
                <h2 class="Heading-Fancy ">
                    <span class='title'>@lang('titles.lessons')</span>
                </h2>
                @include('lessons.lessonsAvatarList')
                <a class="btn btn-default more" href="{{url('/lessons')}}">@lang('labels.more')</a>
            </div>
        </div>

        <div class=" morning fullscreen">
            <div class="Header"></div>
            <div class="Card-Collection row">
                <h2 class="Heading-Fancy ">
                    <span class='title'>@lang('labels.talkshows')</span>
                </h2>
                @include('talkshows.talkshowsList')
                <a class="btn btn-default more" href="{{url('/talkshows')}}">@lang('labels.more')</a>
            </div>

        </div>
        @include('subscribe')
    </div>
@endsection

@section('otherjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>
    <script src="http://vjs.zencdn.net/5.10.7/video.js"></script>
    <script>
        $('img.Card-image').lazyload();

        videojs("my_video").ready(function(){
            $('#my_video').show();
        });
    </script>
@endsection