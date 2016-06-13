@extends('app')

@section('title')
    {{ trans('titles.index') }}
@endsection

@section('content')
    <div class="body ">
        <div class="Header">
        </div>
        <div class="Header"></div>

        @if(Auth::guest())
        <div class="header-container">
        </div>
        @endif

        <div class="BlockMessage BlockMessage-With-Spacing">
            The most concise screencasts for the working developer, updated daily.
        </div>

        <h2 class="Heading-Fancy row">
            <span class='title'>@lang('labels.topics')</span>
        </h2>

        <div class="Card-Collection" >
            @include('topics.topicsList')
            <h2 class="row center">
                <a class="btn btn-default more aniview" av-animation="slideInRight" href="{{url('/topics')}}">@lang('labels.more')</a>
            </h2>
        </div>


        <h2 class="Heading-Fancy row">
            <span class='title'>@lang('labels.talkshows')</span>
        </h2>

        <div class="Card-Collection">
            @include('talkshows.talkshowsList')
            <h2 class="row">
                <a class="btn btn-default more" href="{{url('/talkshows')}}">@lang('labels.more')</a>
            </h2>
        </div>

        @include('menusList')
    </div>
@endsection

@section('otherjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>
    <script>
        $('img.Card-image').lazyload();
    </script>
@endsection