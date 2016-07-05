@extends('app')

@section('title')
    @lang('labels.lessons.title')
@endsection

@section('header')
    <meta name="description" content="@lang('labels.lessons.description')">
    <meta name="Keywords" content="@lang('labels.lessons.keywords')">
@endsection

@section('content')
    <div class="body">
        <div class="Header">
        </div>

        <div class="Header">
        </div>

        <div class="Card-Collection">
            @include('lessons.lessonsAvatarList')
            <?php $pages = $lessons?>
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