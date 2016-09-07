@extends('app')

@section('title')@lang('labels.search.title')@endsection

@section('header')
    <meta name="Keywords" content="@lang('labels.search.keywords')">
@endsection

@section('content')
        <div class="Header"></div>
        <div class="Header"></div>

        @if(count($videos) == 0 )
            <div class="Card-Collection search-result">
                @lang('labels.nothing')
            </div>
        @else
            <h2 class="Heading-Fancy row">
                <span class='title'>@lang('labels.relativeVideos')</span>
            </h2>
            <div class="Card-Collection">
                <?php $readables = $videos; $type = 'video' ?>
                @include('components.readableList')
            </div>

            <center>
                {!! $videos->links() !!}
            </center>
        @endif
        <div class="Header"></div>
        <div class="Header"></div>
@endsection

@section('otherjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>
    <script>
        $('img.Card-image').lazyload();
    </script>
@endsection