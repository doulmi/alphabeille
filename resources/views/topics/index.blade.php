@extends('app')

@section('title')
    @lang('titles.topics')
@endsection

@section('content')
    <div class="body">
        <div class="Header">
        </div>

        <div class="Header">
        </div>

        <div class="Card-Collection">
           @include('topics.topicsList')

            {{--<div >--}}
               <a class="prePage" href="{{$topics->previousPageUrl()}}"><span class="glyphicon glyphicon-chevron-left pre-page-icon"></span></a>
            {{--</div>--}}

            <div class="nextPage">
                <a href="{{$topics->nextPageUrl()}}"><span class="glyphicon glyphicon-chevron-right"></span></a>
            </div>

            <div class="center">
            {!! $topics->links() !!}
            </div>
        </div>
    </div>
@endsection

