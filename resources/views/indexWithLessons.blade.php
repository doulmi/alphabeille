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
            <div class="Header"></div>

            <h1 class="BlockMessage BlockMessage-With-Spacing">
                @lang('labels.learnIntroduction')
            </h1>

            {{--@if(Auth::guest())--}}
            {{--<div class="header-container">--}}
            {{--</div>--}}
            {{--@endif--}}

            <div class="moon-panel hidden-xs">
                <div class="moon"></div>
            </div>

            <div class="Card-Collection">
                <h2 class="Heading-Fancy row">
                    <span class='title'>@lang('labels.minitalks')</span>
                </h2>
                @include('minitalks.minitalksList')
                <a class="btn btn-default more" href="{{url('/minitalks')}}">@lang('labels.more')</a>
            </div>

            {{--<div class="Card-Collection ">--}}
            {{--<h2 class="Heading-Fancy row">--}}
            {{--<span class='title'>@lang('titles.lessons')</span>--}}
            {{--</h2>--}}
            {{--@include('lessons.lessonsAvatarList')--}}
            {{--<a class="btn btn-default more aniview" av-animation="slideInRight"--}}
            {{--href="{{url('/lessons')}}">@lang('labels.more')</a>--}}
            {{--</div>--}}
        </div>

        <div class=" night-fall fullscreen">
            <div class="Header"></div>
            <div class="sunny-panel hidden-xs">
                <div class="sunny"></div>
            </div>

            <div class="Card-Collection">
                <h2 class="Heading-Fancy row">
                    <span class='title'>@lang('titles.lessons')</span>
                </h2>
                @include('lessons.lessonsAvatarList')
                <a class="btn btn-default more" href="{{url('/lessons')}}">@lang('labels.more')</a>
            </div>
        </div>

        <div class=" morning fullscreen">
            <div class="Header"></div>
            <div class="Card-Collection">
                <h2 class="Heading-Fancy row">
                    <span class='title'>@lang('labels.talkshows')</span>
                </h2>
                @include('talkshows.talkshowsList')
                <a class="btn btn-default more" href="{{url('/talkshows')}}">@lang('labels.more')</a>
            </div>

        </div>

        {{--        @include('menusList')--}}
        @include('subscribe')
    </div>
@endsection

@section('otherjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>
    <script>
        $('img.Card-image').lazyload();
    </script>
@endsection