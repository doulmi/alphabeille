@extends('app')

@section('title')
    @lang('labels.collect.title')
@endsection

@section('content')
    <div class="night">
        <div class="Header">
        </div>
        <div class="Header"></div>

        <h2 class="Heading-Fancy row">
            <span class='title'>@lang('labels.collectLessons')</span>
        </h2>
        @if( count($talkshows) == 0 && count($lessons) == 0 )
            <div class="Card-Collection search-result">
            @lang('labels.nothing')
            </div>
            <div class="Header"></div>
        @endif

        @if(count($lessons) != 0)
            <div class="Card-Collection" >
                @include('lessons.lessonsAvatarList')

                @if(count($lessons) > 8)
                <h2 class="row center">
                    <a class="btn btn-default more" href="{{url('/lessons/collect')}}">@lang('labels.more')</a>
                </h2>
                @endif
            </div>
        @endif

        <h2 class="Heading-Fancy row">
            <span class='title'>@lang('labels.collectTalkshows')</span>
        </h2>
        @if( count($talkshows) != 0)
            <div class="Card-Collection">
                @include('talkshows.talkshowsList')

                @if( count($talkshows) > 8)
                <h2 class="row center">
                    <a class="btn btn-default more " href="{{url('/talkshows/collect')}}">@lang('labels.more')</a>
                </h2>
                @endif
            </div>
        @else
            <div class="Card-Collection search-result">
                @lang('labels.nothing')
            </div>
            <div class="Header"></div>
        @endif

        <h2 class="Heading-Fancy row">
            <span class='title'>@lang('labels.collectMinitalks')</span>
        </h2>
        @if( count($minitalks) != 0)
            <div class="Card-Collection">
                @include('minitalks.minitalksList')

                @if( count($minitalks) > 8)
                <h2 class="row center">
                    <a class="btn btn-default more" href="{{url('/minitalks/collect')}}">@lang('labels.more')</a>
                </h2>
                @endif
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