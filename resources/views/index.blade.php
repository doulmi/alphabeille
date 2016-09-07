@extends('app')

@section('title')@lang('labels.index.title')@endsection

@section('header')
    <meta name="description" content="@lang('labels.index.description')">
    <meta name="Keywords" content="@lang('labels.index.keywords')">
    <meta name="google-site-verification" content="p4OOrrT_9YZ-IQLDF02ChLQuNHEW7xodyKVeZoe8FU8"/>
    <meta name="baidu-site-verification" content="4Y6Akg4Bz5"/>
    <meta property="qc:admins" content="326707660761400152514456375"/>
@endsection

@section('content')
    <div class="body">
        <div class="night">
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
                               controls preload data-setup='{ "aspectRatio":"1920:1080" }'
                               data-setup='{"language":"fr"}'>
                            <source src="http://o9dnc9u2v.bkt.clouddn.com/videos/howToUse.mp4"
                                    type='video/mp4'>
                        </video>
                    </div>
                </div>
                <div class="jumb-footer"></div>
                <div class="jumb-bg3"></div>
            </div>
        </div>
        <div class="morning">
            <div class="Header"></div>

            <div class="Card-Collection row">
                <h2 class="Heading-Fancy ">
                    <span class='title'>@lang('labels.videos')</span>
                </h2>
                <?php $readables = $videos; $type = 'video' ?>
                @include('components.readableList')
                <a class="btn btn-default more" href="{{url('/videos')}}">@lang('labels.more')</a>
            </div>
        </div>

        <div class="night-fall">
            <div class="Header"></div>

            <div class="Card-Collection row">
                <h2 class="Heading-Fancy row">
                    <span class='title'>@lang('labels.minitalks')</span>
                </h2>
                <?php $readables = $minitalks; $type = 'minitalk' ?>
                @include('components.readableList')
                <a class="btn btn-default more" href="{{url('/minitalks')}}">@lang('labels.more')</a>
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

        videojs("my_video").ready(function () {
            $('#my_video').show();
        });
    </script>
@endsection