@extends('app')

@section('title')
    @lang('labels.topics.title')
@endsection

@section('header')
    <meta name="description" content="@lang('labels.topics.description')">
    <meta name="Keywords" content="@lang('labels.topics.keywords')">
@endsection

@section('content')
    <div class="body">
        <div class="Header">
        </div>

        <div class="Header">
        </div>

        <div class="Card-Collection">
            <?php $readables = $topics; $type = 'topic'?>
           @include('utils.readableList')

            <?php $pages = $topics; ?>
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

