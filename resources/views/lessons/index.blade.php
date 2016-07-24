@extends('app')

@section('title')
    @lang('labels.lessons.title')
@endsection

@section('header')
    <meta name="description" content="@lang('labels.lessons.description')">
    <meta name="Keywords" content="@lang('labels.lessons.keywords')">
@endsection

@section('content')
    <div class="night-fall">
        <div class="Header">
        </div>

        <div class="Header">
        </div>

        <h2 class="Heading-Fancy row">
            <span class="Heading-Fancy-subtitle">
                @lang('labels.lessonSubtitle')
            </span>
            <span class='title'>@lang('labels.lessons')</span>
        </h2>

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