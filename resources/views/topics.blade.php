@extends('app')

@section('title')
    {{ trans('titles.topics') }}
@endsection

@section('content')
    <div class="container-fluid body">
        <div class="toplane">
            <h1 class="white center">{{ trans('titles.topics') }}</h1>
        </div>
        <div class="topics container">
            <center>
                <div class="row">
                    @foreach($topics as $i=>$topic)
                        <div class="col-md-3 col-sm-4 col-xs-6" id="topic-container">
                            <div class="topic">
                                <img src="{{$topic->avatar}}">
                                <div class="topic-content">
                                    <a href="#" class="topic-header">{{$topic->title}}</a>
                                    <div class="topic-body">{{ substr($topic->description, 0, 50) }}</div>
                                    <div class="topic-footer">
                                        <span class="topic-audio-count">{{ $topic->lessonCount()}} 个文件</span>

                                        <div class="topic-right hidden-xs">
                                        <span class="topic-view">
                                            <span class="glyphicon glyphicon-eye-open"><span
                                                        class="g-font">{{ $topic->views() }}</span></span>
                                        </span>

                                        <span class="topic-like">
                                            <span class="glyphicon glyphicon-heart"><span
                                                        class="g-font">{{ $topic->likes() }}</span></span>
                                        </span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {!! $topics->links() !!}
            </center>
        </div>
    </div>
@endsection

@section('otherjs')
    {{--<script>--}}
        {{--var topicPage = 1;--}}
        {{--var topicContainer = $('#topic-container');--}}

        {{--$(window).scroll(function () {--}}
            {{--var winHeight = $(window).height();--}}
            {{--var scrollT = $(window).scrollTop(); //滚动条top--}}
            {{--var pageHeight = $(document.body).height(); //页面总高度--}}
            {{--var shouldLoad = (pageHeight - winHeight - scrollT) / winHeight < 0.8;--}}

            {{--console.log(shouldLoad);--}}
            {{--if( shouldLoad ) {--}}
                {{--topicPage ++;--}}
                {{--$.getJSON('{{url('api/topics')}}' + '?page=' + topicPage, function(response) {--}}
                {{--});--}}
            {{--}--}}
        {{--});--}}
    {{--</script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.21/vue.js"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.0/vue-resource.min.js"></script>--}}

    {{--<script>--}}
    {{--var vm = new Vue({--}}
    {{--el : 'body',--}}

    {{--data : {--}}
    {{--topics : [],--}}
    {{--load: true,--}}
    {{--content: false--}}
    {{--},--}}

    {{--created() {--}}
    {{--this.$http.get('{{url('/api/topics?num=8' )}}', function(response) {--}}
    {{--this.topics = response['data'];--}}
    {{--}.bind(this));--}}

    {{----}}
    {{--}--}}
    {{--});--}}
    {{--</script>--}}
@endsection
