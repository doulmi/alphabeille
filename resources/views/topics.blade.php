@extends('app')

@section('title')
    {{ trans('titles.index') }}
@endsection

@section('content')

    <div class="header">
    </div>

    <div class="continer body">
        {{--<center>--}}
            <div class="container topics">
                <div class="row">
                    <h3 class="white pull-left">{{ trans('labels.topics') }}</h3>
                    <h3 class="white  pull-right"><a class="more" href="{{url('topics')}}">{{ trans('labels.more') }}</a></h3>
                </div>
                <div class="row">
                    <div class="col-md-3 col-xs-6" v-for="topic in topics">
                        <div class="topic">
                            <img src="@{{topic['avatar']}}">
                            <div class="topic-content">
                                <a href="#" class="topic-header">@{{topic['title']}}</a>
                                <div class="topic-body">@{{topic['description'].substring(0, 200)}}</div>
                                <div class="topic-footer">
                                    <span class="topic-audio-count ">@{{ topic['lessons'] }} 个文件</span>

                                    <div class="topic-right hidden-xs ">
                                        <span class="topic-view">
                                            <span class="glyphicon glyphicon-eye-open"><span class="g-font">@{{ topic['views'] }} </span></span>
                                        </span>

                                        <span class="topic-like">
                                            <span class="glyphicon glyphicon-heart"><span class="g-font">@{{ topic['likes'] }} </span></span>
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection

@section('otherjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.21/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.0/vue-resource.min.js"></script>

    <script>
        var vm = new Vue({
            el : 'body',

            data : {
                topics : []
            },

            ready() {
                this.$http.get('{{url('/api/topics?num=8' )}}', function(data) {
                    this.topics = data;
                }.bind(this));
            }
        });
    </script>
@endsection
