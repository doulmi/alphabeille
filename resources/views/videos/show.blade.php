@extends('app')

@section('title'){{ $video->title }}@endsection

@section('header')
    <meta name="description" content="{{$video->description}}">
    <meta name="Keywords" content="{{ $video->keywords }}">
@endsection

@section('content')
    <div class="">
        <div class="container video-show">
            <?php $canRead = $video->free || (!Auth::guest() && Auth::user()->level() > 1) ?>
            <div class="Header"></div>
            <h1 class="center">
                {{ $video ->title }}
            </h1>

            <div class="author">
                Par <a href="{{url('/')}}">alpha-beille.com</a> | {{$video->created_at}}
            </div>
            @if($canRead)
                <div class="row">
                    <div class="col-md-6">
                        <video id="my_video" class="video-js vjs-default-skin"
                               controls preload data-setup='{ "aspectRatio":"1920:1080" }'
                               data-setup='{"language":"fr"}'>
                            <source src="{{$video->video_url}}" type='video/mp4'>
                        </video>
                    </div>

                    <div class="col-md-6">
                        <div class="video-content grey">
                            {!! $video->parsed_content !!}
                        </div>
                    </div>
                </div>
            @else
                <video id="my_video" class="video-js vjs-default-skin"
                       controls preload data-setup='{ "aspectRatio":"1920:1080" }'
                       data-setup='{"language":"fr"}'>
                    <source src="{{$video->video_url}}" type='video/mp4'>
                </video>

                @include('blockContent')
            @endif

            @if(!Auth::guest())
                <div class="center">
                    <a href="#" data-tooltips="@lang('labels.favorite')" @click.stop.prevent="favoriteEvent">
                        <div class="heart" v-bind:class="favorite"></div>
                    </a>

                    @if(!$punchin)
                        <a id="punchinlink" class="hidden-xs fancy-button" href="#"
                           data-tooltips="@lang('labels.punchin')"
                           @click.stop.prevent="punchinEvent">
                            <div class="left-frills frills"></div>
                            <div class="button-frilles">@lang('labels.punchin')</div>
                            <div class="right-frills frills"></div>
                        </a>
                    @endif

                    <a href="#" data-tooltips="@lang('labels.collect')" @click.stop.prevent="collectEvent">
                        <div class="collect" v-bind:class="collect"></div>
                    </a>

                    @if(!$punchin)
                        <a id="punchinlink" class="visible-xs fancy-button" href="#"
                           data-tooltips="@lang('labels.punchin')"
                           @click.stop.prevent="punchinEvent">
                            <div class="left-frills frills"></div>
                            <div class="button-frilles">@lang('labels.punchin')</div>
                            <div class="right-frills frills"></div>
                        </a>
                    @endif
                </div>
                <div class="share-component share-panel" data-sites="wechat, weibo ,facebook"
                     data-description="@lang('labels.shareTo')" data-image="{{$video->avatar}}">
                    @lang('labels.share'):
                </div>
            @endif
        </div>

        <div class="Card-Collection">
            {{--推荐部分--}}
            <div class="Header"></div>
            <h2 class="Heading-Fancy row">
                <span class='title black'>{{ trans('labels.suggestVideos')}}</span>
            </h2>
            @include('utils.readableList')

            <div id="disqus_thread">
                <h1 class="black">@lang('labels.comments')</h1>
                @if(Auth::guest())
                    <div class="center">
                        <a href="{{url('login')}}">@lang('labels.login')</a>
                        @lang('labels.loginToReply')
                    </div>
                @else
                    <div class="media">
                        <div class="media-left">
                            <a href="#">
                                <img src="{{Auth::user()->avatar}}" alt="avatar"
                                     class="img-circle media-object avatar hidden-xs">
                            </a>
                        </div>

                        <div class="media-body">
                            <form v-on:submit="onPostComment" method="POST"
                                  id="replyForm">
                                {{csrf_field()}}

                                <textarea name="content" data-provide="markdown" rows="10" v-model="newPost.content"
                                          placeholder="@lang('labels.addComment')" id="comment-content"></textarea>
                                <input type="hidden" name="id" value="{{$video->id}}">
                                <button type="submit" class="pull-right btn btn-submit">@lang('labels.reply')</button>
                            </form>
                        </div>
                    </div>
                @endif

                <div class="comments" v-if="commentVisible">
                    <ul id="comments">
                        <li v-for="comment in comments" class="comment">
                            <div class="media">
                                <a class="media-left">
                                    <img v-bind:src="comment.avatar" alt="avatar"
                                         class="img-circle media-object avatar">
                                </a>

                                <div class="media-body">
                                    <h5 class="media-heading">
                                        @{{comment.name}}
                                    </h5>
                                    <p class="discuss-content">
                                        @{{{ comment.content }}}
                                    </p>
                                    <span class="time">@{{comment.created_at}}</span>
                                </div>
                                @if(!Auth::guest())
                                    <div class="comment-footer">
                                        <button class="btn btn-reply"
                                                @click="reply(comment.userId,comment.name)">@lang('labels.reply')</button>
                                    </div>
                                @endif
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="Header hidden-xs"></div>
            </div>
        </div>
        <div id='goTop'></div>
        @endsection

        @section('otherjs')
            <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.0/vue-resource.min.js"></script>
            <script src="http://vjs.zencdn.net/5.10.7/video.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>

            <script>
                $('#goTop').goTop();

                $('img.Card-image').lazyload();

                function removeHTML(strText) {
                    var regEx = /<[^>]*>/g;
                    return strText.replace(regEx, "");
                }

                $(function () {
                    $(".heart").on("click", function () {
                        $(this).toggleClass("is-active");
                    });
                    $(".collect").on("click", function () {
                        $(this).toggleClass("is-active");
                    });

                    $(".fancy-button").mousedown(function () {
                        $(this).bind('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd', function () {
                            $(this).removeClass('active');
                        });
                        $(this).addClass("active");
                    });
                });

                var player;
                videojs("my_video").ready(function () {
                    player = this;
                    player.play();
                    $('#my_video').show();
                });

                Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
                new Vue({
                    el: 'body',
                    data: {
                        points: [],
                        comments: [],
                        favorite: '{{$like ? 'is-active' : ''}}',
                        isFavorite: '{{$like}}',
                        collect: '{{$collect ? 'is-active' : ''}}',
                        isCollect: '{{$collect}}',
                        newComment: {
                            name: '{{Auth::user() ? Auth::user()->name : ''}}',
                            avatar: '{{Auth::user() ? Auth::user()->avatar : ''}}',
                            content: ''
                        },

                        newPost: {
                            id: '{{$video->id}}',
                            content: ''
                        }
                    },

                    ready() {
                        var pointStr = '{{$video->points}}';
                        this.points = pointStr.split(',');

                        this.$http.get('{{url($type . "Comments/" . $video->id)}}', function (response) {
                            this.comments = response;
                        }.bind(this));
                    },

                    computed: {
                        commentVisible() {
                            return this.comments instanceof Array && this.comments.length > 0;
                        }
                    },

                    methods: {
                        reply(userId, userName) {
                            window.location.href = "#replyForm";
                            this.newPost.content = '@' + userName;
                        },
                        seekTo(no) {
                            var time = this.points[no];
                            console.log(time);
                            player.currentTime(time);
                        },

                        favoriteEvent() {
                            this.$http.post('{{url("/" . $type . "s/" . $video->id . '/favorite')}}', function (response) {
                            }.bind(this));
                        },

                        collectEvent() {
                            this.$http.post('{{url("/". $type . "s/" . $video->id . '/collect')}}', function (response) {
                            }.bind(this));
                        },

                        punchinEvent() {
                            var punchin = $('#punchin');

                            this.$http.post('{{url("/". $type . "s/" . $video->id . '/punchin')}}', function (response) {
                                punchin.html(parseInt(punchin.html()) + 1);
                                var series = response.series;
                                var isBreakup = response.break;
                                if (isBreakup) {
                                    @if(!Auth::guest())
                                    toastr.success("@lang('labels.punchinSuccess')" + "@lang('labels.breakup')" + "@lang('labels.continuePunchin', ['day' => Auth::user()->series + 1])");
                                    @endif
                                }
                                $('#punchinlink').hide();
                            }.bind(this));
                        },

                        onPostComment: function (e) {
                            e.preventDefault();

                            var comment = this.newComment;
                            var post = this.newPost;

                            if (removeHTML(post.content).length < 10) {
                                toastr.error("@lang('labels.tooShortComment')");
                                return;
                            }
                            comment.content = post.content;

                            this.$http.post("{{url('/'. $type .'Comments')}}", post, function (data) {
                                this.comments.unshift(comment);
                                this.newPost.content = '';

                                toastr.success("@lang('labels.feelFreeToComment')", "@lang('labels.commentSuccess')");
                                comment = {
                                    name: '{{Auth::user() ? Auth::user()->name : ''}}',
                                    avatar: '{{Auth::user() ? Auth::user()->avatar: ''}}',
                                    body: ''
                                };
                            }.bind(this))
                        }
                    }
                });
            </script>
@endsection

