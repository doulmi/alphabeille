@extends('app')

@section('title')
    {{ trans('titles.index') }}
@endsection

@section('content')
    <div class="body">
        <div class="Header">
        </div>

        <div class="header-container">
        </div>

        <div class="BlockMessage BlockMessage-With-Spacing">
            The most concise screencasts for the working developer, updated daily.
        </div>

        <h2 class="Heading-Fancy row">
            <span class='title'>{{ trans('labels.topics') }}</span>
        </h2>

        <div class="Card-Collection">
            @include('topics.topicsList')
            <h2 class="row">
                <a class="btn btn-default more" href="{{url('/topics')}}">{{trans('labels.more')}}</a>
            </h2>
        </div>


        <h2 class="Heading-Fancy row">
            <span class='title'>{{ trans('labels.talkshows') }}</span>
        </h2>

        <div class="Card-Collection">
            @include('talkshows.talkshowsList')
            <h2 class="row">
                <a class="btn btn-default more" href="{{url('/talkshows')}}">{{trans('labels.more')}}</a>
            </h2>
        </div>


        @include('menusList')
    </div>
@endsection
