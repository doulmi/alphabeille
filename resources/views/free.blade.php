@extends('app')

@section('title')@lang('labels.free.title')@endsection

@section('header')
    <meta name="description" content="@lang('labels.free.description')">
    <meta name="Keywords" content="@lang('labels.free.keywords')">
@endsection

@section('content')
    <div class="night">
        <div class="Header">
        </div>
        <div class="Header"></div>

        <div class="moon-panel hidden-xs">
            <div class="moon"></div>
        </div>
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

                <?php $readables = $lessons; $type = 'lesson'?>
                @include('components.readableList')

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
                <?php $readables = $talkshows; $type = 'talkshow' ?>
                @include('components.readableList')

                <h2 class="row center">
                    <a class="btn btn-default more aniview" av-animation="slideInRight" href="{{url('/talkshows/free')}}">@lang('labels.more')</a>
                </h2>
            </div>
        @endif

        <h2 class="Heading-Fancy row">
            <span class='title'>@lang('labels.freeMinitalks')</span>
        </h2>
        @if( count($minitalks) != 0)
            <div class="Card-Collection">
                <?php $readables = $minitalks; $type = 'minitalk'?>
                @include('components.readableList')

                <h2 class="row center">
                    <a class="btn btn-default more aniview" av-animation="slideInRight" href="{{url('/minitalks/free')}}">@lang('labels.more')</a>
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