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
            <a id="real">real</a>

            <div class="author">
                Par <a href="{{url('/')}}">alpha-beille.com</a> | {{$video->created_at}}
            </div>
            @if($canRead)
                <div class="row">
                    <div class="col-md-7">
                        <video id="my_video" class="video-js vjs-default-skin"
                               controls preload
                               data-setup='{ "aspectRatio":"1920:1080", "playbackRates": [0.5, 0.75, 1, 1.25, 1.5, 2] }'>
                            <source src="{{$video->video_url}}" type='video/mp4'>
                        </video>
                        <div class="subtitle">
                            <div class="center">
                                <p v-show="fr">@{{{currentFr}}}</p>
                                <p v-show="zh">@{{{currentZh}}}</p>
                            </div>
                            <div class="control-panel">
                                <a href="#" :disabled="active == 0" @click.stop.prevent='prev'><i
                                            class="glyphicon glyphicon-chevron-left"></i></a>
                                <a href="#" @click.stop.prevent='repeat' :class="repeatOne ? 'active' : '' ">重复单句</a>
                                <a href="#" :disabled="active == pointsCount - 1 " @click.stop.prevent='next'><i
                                            class="glyphicon glyphicon-chevron-right"></i></a>

                                <a href="#" @click.stop.prevent='toggleFr' :class="fr ? 'active' : ''">法</a>
                                <a href="#" @click.stop.prevent='toggleZh' :class="zh ? 'active' : ''">中</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="video-content grey">
                            <table>
                                <tbody>
                                <tr v-for="line in linesFr">
                                    <td class='width40 '><a href='#@{{ $index }}' @click.stop.prevent='seekTo($index)'
                                                            class='seek-btn'
                                                            :class="played.indexOf($index) > -1 > 'active' : ''"></a>
                                    </td>
                                    <td>
                                        <p :class="active == $index ? 'active' : ''">@{{{line}}}</p>
                                    </td>
                                </tr>
                                {{--@endforeach--}}
                                </tbody>
                            </table>
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

            <ul id="showpop-menu" class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                <li>
                    <div id="dict_result">
                        <div style="float:left;">
                            <table>
                                <tbody>
                                <tr>
                                    <td id="word_text"></td>
                                    <td>
                                        <ul class="controls">
                                            <li><a class="audioButton" href="/player/and.mp3"></a></li>
                                        </ul>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div id="word_result"></div>
                    </div>
                </li>
            </ul>
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

//                    $("span").popover({title: $(this).html().trim().toLowerCase(), content: "Blabla", trigger: "click", placement: "bottom"});

                    var popover = $('#showpop-menu');

                    var word_text = $('#word_text');
                    var word_result = $('#word_result');
                    $(document).click(function (e) {
                        var target = $(e.target);
                        var tag = $(target[0]).prop('tagName');
                        if (tag != 'SPAN' && target != popover) {
                            popover.hide();
                        }
                    });

                    //TODO : POPOVER
                    $('span').click(function () {
                        var word = $(this).html().trim().toLowerCase();
                        var offset = $(this).offset();
                        popover.offset({
                            top: offset.top,
                            left: offset.left
                        });
                        word_text.html(word);
                        word_result.html("@lang('labels.loading')");
                        popover.show();
                        var that = this;
                        $.get("{{url('api/words')}}" + "/" + word, function (response) {
//                            that.attr("data-content", response);
                            word_result.html(response['msg']);
                        });
                    })
                });

                var player;
                var currentTime;

                Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
                // 注册 partial
                var vm = new Vue({
                    el: 'body',
                    data: {
                        points: [],
                        lines: [],
                        linesFr: [],
                        linesZh: [],
                        fr: true,
                        zh: true,
                        pointsCount: 0,
                        played: [],    //  保存已经播放过的橘子
                        active: -1,
                        currentFr: "開始影片後，點擊或框選字幕可以立即查詢單字",
                        currentZh: "",
                        comments: [],
                        favorite: '{{$like ? 'is-active' : ''}}',
                        isFavorite: '{{$like}}',
                        collect: '{{$collect ? 'is-active' : ''}}',
                        isCollect: '{{$collect}}',
                        lineActive: '',
                        repeatOne: false,
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
                        this.pointsCount = this.points.length;

                        this.linesFr = "{!!$video->parsed_content!!}".split('||');
                        this.linesZh = "{!!$video->parsed_content_zh!!}".split('||');

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
                        },

                        timeupdate() {
                            var currentTime = player.currentTime();
                            for (var i = 0; i < this.points.length; i++) {
                                if (this.repeatOne) {
                                    if (currentTime >= this.points[this.active + 1]) {
                                        player.currentTime(this.points[this.active]);
                                    }
                                }
                                if (this.active != i && currentTime >= this.points[i]) {
                                    this.active = i;
                                    this.currentZh = this.linesZh[i];
                                    this.currentFr = this.linesFr[i];
                                }

                            }
                        },

                        prev() {
                            if (this.active - 1 >= 0) {
                                player.currentTime(this.points[this.active - 1]);
                            }
                        },

                        next() {
                            if (this.active + 1 < this.pointsCount) {
                                player.currentTime(this.points[this.active + 1]);
                            }
                        },

                        repeat() {
                            this.repeatOne = !this.repeatOne;
                            console.log(this.repeatOne);
                        },

                        toggleFr() {
                            this.fr = !this.fr;
                        },

                        toggleZh() {
                            this.zh = !this.zh;
                        }
                    }
                });

                videojs("my_video").ready(function () {
                    player = this;

                    player.on('timeupdate', vm.timeupdate);
                    player.play();
                    $('#my_video').show();
                });
            </script>
@endsection

