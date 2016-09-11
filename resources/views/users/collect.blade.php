@extends('app')

@section('title')
    @lang('labels.collect.title')
@endsection

@section('content')
    <div class="Header"></div>
    <div class="Header"></div>

    @if(count($minitalks) == 0 && count($videos) == 0)
        <div class="Card-Collection search-result ">
            @lang('labels.nothingCollect')
        </div>

    @else
        @if( count($minitalks) != 0)
            <h2 class="Heading-Fancy">
                <span class='title'>@lang('labels.collectMinitalks')</span>
            </h2>
            <div class="Card-Collection">
                <?php $readables = $minitalks; $type = 'minitalk'?>
                @include('components.readableList')

                @if( count($minitalks) > 8)
                    <h2 class="row center">
                        <a class="btn btn-default more" href="{{url('/minitalks/collect')}}">@lang('labels.more')</a>
                    </h2>
                @endif
            </div>
        @endif

        @if( count($videos) != 0)
            <div class="Header"></div>
            <h2 class="Heading-Fancy row">
                <span class='title'>@lang('labels.collectVideos')</span>
            </h2>
            <div class="Card-Collection">
                <?php $readables = $videos; $type = 'video'?>
                @include('components.readableList')

                @if( count($videos) > 8)
                    <h2 class="row center">
                        <a class="btn btn-default more" href="{{url('/videos/collect')}}">@lang('labels.more')</a>
                    </h2>
                @endif
            </div>
        @endif
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