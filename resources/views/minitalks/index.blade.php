@extends('app')

@section('title')
    @lang('labels.minitalks.title')
@endsection

@section('header')
    <meta name="description" content="@lang('labels.minitalks.description')">
    <meta name="Keywords" content="@lang('labels.minitalks.keywords')">
@endsection

@section('content')
    <div class="night">
        <div class="Header">
        </div>

        <div class="Header">
        </div>

        <h2 class="Heading-Fancy row">
            <span class="Heading-Fancy-subtitle">
                @lang('labels.minitalkSubtitle')
            </span>
            <span class='title'>@lang('labels.minitalks')</span>
        </h2>

        <div class="Card-Collection">
            @include('minitalks.minitalksList')
            <?php $pages = $minitalks ?>
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