@extends('app')

@section('title')
    @lang('titles.talkshows')
@endsection

@section('content')
    <div class="body">
        <div class="Header">
        </div>

        <div class="Header">
        </div>

        <div class="Card-Collection">
            @include('talkshows.talkshowsList')

            <div class="prePage">
                <a  href="{{$talkshows->previousPageUrl()}}"><span class="glyphicon glyphicon-chevron-left pre-page-icon"></span></a>
            </div>

            <div class="nextPage">
                <a href="{{$talkshows->nextPageUrl()}}"><span class="glyphicon glyphicon-chevron-right"></span></a>
            </div>

            <div class="center">
                {!! $talkshows->links() !!}
            </div>
        </div>
    </div>
@endsection

