@extends('app')

@section('title')
    @lang('labels.collect.title')
@endsection

@section('content')
    <div class="night fullscreen">
        <div class="Header"></div>
        <div class="Header"></div>

        @if(count($talkshows) == 0 && count($lessons) == 0  && count($minitalks) == 0 && count($videos) == 0)
            <div class="Card-Collection search-result ">
                @lang('labels.nothingCollect')
            </div>
        @else
            @if(count($lessons) != 0)
                <h2 class="Heading-Fancy row">
                    <span class='title'>@lang('labels.collectLessons')</span>
                </h2>
                <div class="Card-Collection">
                    <?php $readables = $lessons; $type = 'lessons'?>
                    @include('utils.readableList')

                    @if(count($lessons) > 8)
                        <h2 class="row center">
                            <a class="btn btn-default more" href="{{url('/lessons/collect')}}">@lang('labels.more')</a>
                        </h2>
                    @endif
                </div>
            @endif

            @if( count($talkshows) != 0)
                <h2 class="Heading-Fancy row">
                    <span class='title'>@lang('labels.collectTalkshows')</span>
                </h2>
                <div class="Card-Collection">
                    <?php $readables = $talkshows; $type = 'talkshows'?>
                    @include('utils.readableList')

                    @if( count($talkshows) > 8)
                        <h2 class="row center">
                            <a class="btn btn-default more " href="{{url('/talkshows/collect')}}">@lang('labels.more')</a>
                        </h2>
                    @endif
                </div>
            @endif

            @if( count($minitalks) != 0)
                <h2 class="Heading-Fancy row">
                    <span class='title'>@lang('labels.collectMinitalks')</span>
                </h2>
                <div class="Card-Collection">
                    <?php $readables = $minitalks; $type = 'minitalks'?>
                    @include('utils.readableList')

                    @if( count($minitalks) > 8)
                        <h2 class="row center">
                            <a class="btn btn-default more" href="{{url('/minitalks/collect')}}">@lang('labels.more')</a>
                        </h2>
                    @endif
                </div>
            @endif

            @if( count($videos) != 0)
                <h2 class="Heading-Fancy row">
                    <span class='title'>@lang('labels.collectVideos')</span>
                </h2>
                <div class="Card-Collection">
                    <?php $readables = $videos; $type = 'videos'?>
                    @include('utils.readableList')

                    @if( count($videos) > 8)
                        <h2 class="row center">
                            <a class="btn btn-default more" href="{{url('/videos/collect')}}">@lang('labels.more')</a>
                        </h2>
                    @endif
                </div>
            @endif
        @endif
    </div>
@endsection

@section('otherjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>
    <script>
        $('img.Card-image').lazyload();
    </script>
@endsection