@extends('app')

@section('title')
    @lang('labels.videos.title')
@endsection

@section('header')
    <meta name="description" content="@lang('labels.videos.description')">
    <meta name="Keywords" content="@lang('labels.videos.keywords')">
@endsection

@section('content')
    <div class="night">
        <div class="Header">
        </div>

        <div class="Header">
        </div>

        <h2 class="Heading-Fancy row">
            <span class="Heading-Fancy-subtitle">
                @lang('labels.videosubtitle')
            </span>
            <span class='title'>@lang('labels.videos')</span>
        </h2>

        <div class="Card-Collection">
            <?php $pages = $readables = $videos; ?>
            @include('readableList')
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