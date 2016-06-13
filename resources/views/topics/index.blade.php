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

            <?php $pages = $topics; ?>
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

