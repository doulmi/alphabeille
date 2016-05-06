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

            <div class="container topics">
                <div class="row">
                    <h3 class="white pull-left">{{ trans('labels.talkshows') }}</h3>
                    <h3 class="white  pull-right"><a class="more" href="{{url('talkshows')}}">{{ trans('labels.more') }}</a></h3>
                </div>

                <div class="row">
                    <div class="col-md-3 col-xs-6" v-for="talkshow in talkshows">
                        <div class="topic">
                            <img src="@{{talkshow['avatar']}}">
                            <div class="topic-content">
                                <a href="#" class="topic-header">@{{talkshow['title']}}</a>
                                <div class="topic-body">@{{talkshow['description'].substring(0, 200)}}</div>
                                <div class="topic-footer">
                                    <span class="topic-audio-count">&nbsp;</span>
                                    <div class="topic-right hidden-xs">
                                        <span class="topic-view">
                                            <span class="glyphicon glyphicon-eye-open"><span class="g-font">@{{ talkshow['views'] }} </span></span>
                                        </span>

                                        <span class="topic-like">
                                            <span class="glyphicon glyphicon-heart"><span class="g-font">@{{ talkshow['likes'] }}</span></span>
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bitch">
                <ul>
                    <li class="layer umbra"><img src="/img/umbra.png"></li>
                    <li class="layer cloud1"><img src="/img/cloud1.png"></li>
                    <li class="layer cloud2"><img src="/img/cloud2.png"></li>
                </ul>

                <div class="container Classes">
                    <div class="row">
                        <div class="Class Economy col-md-2 col-md-offset-3-5 col-xs-6 col-xs-offset-3">
                            <h3 style="margin-top: 0px;">Economy<br>
                                <span>Great for small websites<br> and blogs</span>
                            </h3>
                            <div class="White">
                                <div class="Dots"></div>
                                <h4 class="text-center"><i class="fa fa-eur"></i>1.<span>77/mo</span></h4>
                                <ul>
                                    <li>5GB Mthly Bandwidth</li>
                                </ul>
                            </div>
                            <div class="Gray">
                                <a class="Buy" href="/hosting/new-hosting-account?hostingType=economy">Buy </a>
                            </div>
                        </div>

                        <div class="Class Premium col-md-2 col-md-offset-1 col-xs-6 col-xs-offset-3">
                            <h3 style="margin-top: 0px;">Premium<br>
                                <span>More email addresses,<br>more storage</span>
                            </h3>
                            <div class="White">
                                <div class="Dots"></div>
                                <h4 class="text-center">
                                    <i class="fa fa-eur"></i>2.<span>65/mo</span>
                                </h4>
                                <ul>
                                    <li>10GB Mthly Bandwidth</li>
                                </ul>
                            </div>
                            <div class="Gray">
                                <a class="Buy" href="/hosting/new-hosting-account?hostingType=premium">Buy </a>
                            </div>
                        </div>

                        <div class="Class Business col-md-2 col-md-offset-1 col-xs-6 col-xs-offset-3">
                            <h3 style="margin-top: 0px;">Business<br>
                                <span>Perfect for<br>high-quality content</span>
                            </h3>
                            <div class="White">
                                <div class="Dots"></div>
                                <h4 class="text-center">
                                    <i class="fa fa-eur"></i>7.<span>09/mo</span>
                                </h4>
                                <ul>
                                    <li>20GB Mthly Bandwidth</li>
                                </ul>
                            </div>
                            <div class="Gray">
                                <a class="Buy" href="/hosting/new-hosting-account?hostingType=business">Buy</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        {{--</center>--}}
    </div>
@endsection

@section('otherjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.21/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.0/vue-resource.min.js"></script>

    <script>
        var vm = new Vue({
            el : 'body',

            data : {
                topics : [],
                talkshows : []
            },

            ready() {
                this.$http.get('{{url('/api/topics?num=8' )}}', function(data) {
                    this.topics = data;
                }.bind(this));

                this.$http.get('{{url('/api/talkshows?num=8')}}', function(data) {
                    this.talkshows = data;
                }.bind(this));
            }
        });
    </script>
@endsection
