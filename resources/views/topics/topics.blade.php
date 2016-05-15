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

        <div class="sky">
            <div class="cloud variant-1"></div>
            <div class="cloud variant-2"></div>
            <div class="cloud variant-3"></div>
            <div class="cloud variant-4"></div>
            <div class="cloud variant-5"></div>
            <div class="cloud variant-6"></div>
            <div class="cloud variant-7"></div>
            <div class="cloud variant-8"></div>

            <div class="cloud variant-9"></div>
            <div class="cloud variant-10"></div>
            <div class="cloud variant-11"></div>
        </div>


        <div class="Card-Collection">
           @include('topics.topicsList')

            <div class="prePage">
               <a  href="{{$topics->previousPageUrl()}}"><span class="glyphicon glyphicon-chevron-left pre-page-icon"></span></a>
            </div>

            <div class="nextPage">
                <a href="{{$topics->nextPageUrl()}}"><span class="glyphicon glyphicon-chevron-right"></span></a>
            </div>

            <div class="center">
            {!! $topics->links() !!}
            </div>
        </div>
    </div>
@endsection

