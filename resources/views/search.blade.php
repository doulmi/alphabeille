@extends('app')

@section('title')
    @lang('labels.search.title')
@endsection

@section('header')
    <meta name="Keywords" content="@lang('labels.search.keywords')">
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
                <?php $readables = $lessons; $type = 'lesson'?>
                @include('utils.readableList')
            </div>
        @endif

        <h2 class="Heading-Fancy row">
            <span class='title'>@lang('labels.relativeTalkshows')</span>
        </h2>
        @if( count($talkshows) != 0)
            <div class="Card-Collection">
                <?php $readables = $talkshows; $type = 'talkshow' ?>
                @include('utils.readableList')
            </div>
        @endif

        <h2 class="Heading-Fancy row">
            <span class='title'>@lang('labels.relativeMinitalks')</span>
        </h2>
        @if( count($minitalks) != 0)
            <div class="Card-Collection">
                <?php $readables = $minitalks; $type = 'minitalk'?>
                @include('utils.readableList')
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