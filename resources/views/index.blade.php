@extends('app')

@section('title')
    @lang('labels.index.title')
@endsection

@section('header')
    <meta name="description" content="@lang('labels.index.description')">
    <meta name="Keywords" content="@lang('labels.index.keywords')">
    <meta name="google-site-verification" content="p4OOrrT_9YZ-IQLDF02ChLQuNHEW7xodyKVeZoe8FU8"/>
    <meta name="baidu-site-verification" content="4Y6Akg4Bz5"/>
@endsection

@section('content')
    <div class="body">
        <div class="night fullscreen">
            <div class="jumbotron ad-jumbotron">
                <div class="Header"></div>
                <div class="container">
                    <div class="col-md-6">
                        <h1 class="BlockMessage BlockMessage-With-Spacing left">@lang('labels.learnIntroduction')</h1>
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
                    <div class="col-md-6">
                        <div class="top-48"></div>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/176076198?title=0&byline=0&portrait=0" frameborder="0" webkitallowfullscreen mozallowfullscreen
                                        allowfullscreen></iframe>
                            </div>
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
    <script>
        $('img.Card-image').lazyload();
    </script>
@endsection