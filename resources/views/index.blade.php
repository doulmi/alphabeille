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
                            <div class="search-suggest hidden-xs">
                                <div class="search-suggest-row">
                                    <a class="searchTag" href="{{url('search?keys=Avez+vous+déjà+vu')}}">Avez vous déjà vu</a>
                                    <a class="searchTag" href="{{url('search?keys=Cyprien')}}">Cyprien</a>
                                    <a class="searchTag" href="{{url('search?keys=Excuse')}}">Excuse</a>
                                    <a class="searchTag" href="{{url('search?keys=Un+gars+et+une+fille')}}">Un gars et une fille</a>
                                </div>

                                <div class="search-suggest-row">
                                    <a class="searchTag" href="{{url('search?keys=Bloqués')}}">Bloqués</a>
                                    <a class="searchTag" href="{{url('search?keys=Golden+Moustache')}}">Golden Moustache</a>
                                    <a class="searchTag" href="{{url('search?keys=Palmashow')}}">Palmashow</a>

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

                <ul id="tabs" class="nav nav-tabs hidden-xs" data-tabs="tabs">
                    <li class="active"><a href="#mostViews" data-toggle="tab">@lang('labels.orderByViews')</a></li>
                    <li><a href="#latest" data-toggle="tab">@lang('labels.orderByLatest')</a></li>
                </ul>

                <div id="my-tab-content" class="tab-content">
                    <div id="mostViews" class="tab-pane active">
                        <?php $readables = $videos[1]; $type = 'video' ?>
                        @include('components.readableList')
                    </div>

                    <div id="latest" class="tab-pane ">
                        <?php $readables = $videos[0]; $type = 'video' ?>
                        @include('components.readableList')
                    </div>
                </div>
                <a class="btn btn-default more more-margin" href="{{url('/videos')}}">@lang('labels.more')</a>
            </div>
            <div class="Header"></div>
            <div class="Header"></div>
        </div>

        {{--<div class="night-fall">--}}
            {{--<div class="Header"></div>--}}

            {{--<div class="Card-Collection row">--}}
                {{--<h2 class="Heading-Fancy row">--}}
                    {{--<span class='title'>@lang('labels.minitalks')</span>--}}
                {{--</h2>--}}
                {{--<?php $readables = $minitalks; $type = 'minitalk' ?>--}}
                {{--@include('components.readableList')--}}
                {{--<a class="btn btn-default more more-margin" href="{{url('/minitalks')}}">@lang('labels.more')</a>--}}
            {{--</div>--}}
        {{--</div>--}}
{{--        @include('subscribe')--}}
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