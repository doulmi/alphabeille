@extends('app')

@section('title')
    @lang('labels.free.title')
@endsection

@section('header')
    <meta name="description" content="@lang('labels.free.description')">
    <meta name="Keywords" content="@lang('labels.free.keywords')">
@endsection

@section('content')
    <div class="body">
        <div class="Header">
        </div>
        <div class="Header"></div>

        <h2 class="Heading-Fancy row">
            <span class='title'>@lang('labels.freeLessons')</span>
        </h2>
        @if( count($talkshows) == 0 && count($lessons) == 0 )
            <div class="Card-Collection search-result">
            @lang('labels.nothing')
            </div>
            <div class="Header"></div>
        @endif

        @if(count($lessons) != 0)
            <div class="Card-Collection" >
                @include('lessons.lessonsAvatarList')

                <h2 class="row center">
                    <a class="btn btn-default more aniview" av-animation="slideInRight" href="{{url('/lessons/free')}}">@lang('labels.more')</a>
                </h2>
            </div>
        @endif

        <h2 class="Heading-Fancy row">
            <span class='title'>@lang('labels.freeTalkshows')</span>
        </h2>
        @if( count($talkshows) != 0)
            <div class="Card-Collection">
                @include('talkshows.talkshowsList')

                <h2 class="row center">
                    <a class="btn btn-default more aniview" av-animation="slideInRight" href="{{url('/talkshows/free')}}">@lang('labels.more')</a>
                </h2>
            </div>
        @endif

    </div>
@endsection

@section('otherjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>
    <script>
        $('img.Card-image').lazyload();
    </script>
@endsection