@extends('app')

@section('title')
    @lang('labels.basicCourses.title')
@endsection

@section('header')
    <meta name="description" content="@lang('labels.basicCourses.description')">
    <meta name="Keywords" content="@lang('labels.basicCourses.keywords')">
@endsection

@section('content')
    <div class="body">
        <div class="night">
            <div class="jumbotron ad-jumbotron">
                <div class="Header"></div>
                <div class="container">
                    <div class="col-md-6 top-80">
                        <h1 class="BlockMessage BlockMessage-With-Spacing margin-top-80">@lang('labels.learnIntroduction')</h1>
                    </div>
                </div>
            </div>

            <div class="Card-Collection row">
                <div class="Header"></div>
                <h2 class="Heading-Fancy ">
                    <span class='title'>@lang('labels.videos')</span>
                </h2>
                <?php $readables = $videos; $type = 'video' ?>
                @include('components.readableList')
                <a class="btn btn-default more" href="{{url('/videos')}}">@lang('labels.more')</a>
            </div>
        </div>

        <div class="night-fall">
            <div class="Header"></div>
            <div class="Card-Collection row">
                <h2 class="Heading-Fancy ">
                    <span class='title'>@lang('titles.lessons')</span>
                </h2>
                <?php $readables = $lessons; $type = 'lesson' ?>
                @include('components.readableList')
                <a class="btn btn-default more" href="{{url('/lessons')}}">@lang('labels.more')</a>
            </div>
        </div>

        <div class="morning">
            <div class="Header"></div>

            <div class="Card-Collection row">
                <h2 class="Heading-Fancy row">
                    <span class='title'>@lang('labels.minitalks')</span>
                </h2>
                <?php $readables = $minitalks; $type = 'minitalk' ?>
                @include('components.readableList')
                <a class="btn btn-default more" href="{{url('/minitalks')}}">@lang('labels.more')</a>
            </div>
        </div>
    </div>
@endsection

@section('otherjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>
    <script src="http://vjs.zencdn.net/5.10.7/video.js"></script>
    <script>
        $('img.Card-image').lazyload();

        videojs("my_video").ready(function(){
            $('#my_video').show();
        });
    </script>
@endsection