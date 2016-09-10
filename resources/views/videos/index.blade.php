@extends('app')

@section('title')@lang('labels.videos.title')@endsection

@section('header')
    <meta name="description" content="@lang('labels.videos.description')">
    <meta name="Keywords" content="@lang('labels.videos.keywords')">
@endsection

@section('content')
    <div class="Header"></div>
    <div class="Header"></div>

    <h2 class="Heading-Fancy">
        <span class="Heading-Fancy-subtitle">@lang('labels.videosubtitle')</span>
        <span class='title'>@lang('labels.videos')</span>
    </h2>

    <div class="Card-Collection">
        <div class="filter-container">
        <a class="btn btn-default filter-btn {{(Request::get('orderBy') == '' || (request::get('orderBy') == 'latest')) ? 'current' : ''}}" href="{{url('videos?orderBy=latest')}}">@lang('labels.orderByLatest')</a>
        <a class="btn btn-default filter-btn {{Request::get('orderBy') == 'views' ? 'current' : ''}}" href="{{url('videos?orderBy=views')}}">@lang('labels.orderByViews')</a>
        </div>
        @include('components.readableList')
        @include('components.pageNavigator')
    </div>
    <input type="hidden" name="orderBy" value="{{Request::get('orderBy')}}"/>
    <div class="Header"></div>
    <div class="Header"></div>
@endsection

@section('otherjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>
    <script>
        $('img.Card-image').lazyload();
    </script>
@endsection