@extends('app')

@section('title')
    @lang('labels.search.title')
@endsection

@section('header')
    <meta name="Keywords" content="@lang('labels.search.keywords')">
@endsection

@section('content')
    <div class="body">
        <div class="Header">
        </div>
        <div class="Header"></div>

        <h2 class="Heading-Fancy row">
            <span class='title'>@lang('labels.relativeLessons')</span>
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
            </div>
        @endif

        <h2 class="Heading-Fancy row">
            <span class='title'>@lang('labels.relativeTalkshows')</span>
        </h2>
        @if( count($talkshows) != 0)
            <div class="Card-Collection">
                @include('talkshows.talkshowsList')
            </div>
        @endif

        <h2 class="Heading-Fancy row">
            <span class='title'>@lang('labels.relativeMinitalks')</span>
        </h2>
        @if( count($minitalks) != 0)
            <div class="Card-Collection">
                @include('minitalks.minitalksList')
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