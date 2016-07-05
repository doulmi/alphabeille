@extends('app')

@section('title')
    @lang('labels.talkshows.title')
@endsection

@section('header')
    <meta name="description" content="@lang('labels.talkshows.description')">
    <meta name="Keywords" content="@lang('labels.talkshows.keywords')">
@endsection

@section('content')
    <div class="body">
        <div class="Header">
        </div>

        <div class="Header">
        </div>

        <div class="Card-Collection">
            @include('talkshows.talkshowsList')
            <?php $pages = $talkshows ?>
            @include('utils.pageNavigator')
        </div>
    </div>
@endsection

@section('otherjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>
    <script>
        $('img.Card-image').lazyload();
    </script>
@endsection