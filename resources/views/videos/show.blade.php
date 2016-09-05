@extends('app')

@section('title'){{ $readable->title }}@endsection

@section('header')
    <meta name="description" content="{{$readable->description}}">
    <meta name="Keywords" content="{{ $readable->keywords }}">
@endsection

@section('content')
    <div class="container-fluid grey">
        <div class="Header"></div>
        {{-- 1.免费 or 2.有权限 or 3.自己翻译的 --}}
        <?php $canRead = $readable->free || (Auth::user() && (Auth::user()->can('videos.subs') || $readable->translator_id == Auth::user()->id)) ?>
        <div class="container">
            <div class="row video-show">
                <div class="col-md-7" id="videoPanel">
                    <a href="{{url('videos/level/' . $readable->level)}}"><span
                                class="label label-success {{$readable->level}}">@lang('labels.' . $readable->level)</span></a>
                    <span class="">
                        <i class="glyphicon glyphicon-headphones"></i>
                        <span class="g-font">{{ Redis::get($type . ':view:' . $readable->id) }}</span>
                    </span>
                    @if($youtube)
                        {{--<div id="video-placeholder"></div>--}}
                        <div class="video-container">
                            <div id="video-placeholder"></div>
                        </div>
                        {{--<div id="video-placeholder" class="embed-responsive embed-responsive-16by9"></div>--}}
                    @else
                        <video id="my_video" class="video-js vjs-default-skin"
                               controls preload
                               data-setup='{ "aspectRatio":"1920:1080", "playbackRates": [0.5, 0.75, 1, 1.25, 1.5, 2] }'>
                            <source src="{{$readable->video_url}}" type='video/mp4'>
                        </video>
                    @endif
                    <div class="subtitle">
                        <div class="center">
                            @if($canRead)
                                <p v-show="fr">@{{{currentFr}}}</p>
                                <p v-show="zh">@{{{currentZh}}}</p>
                            @endif
                        </div>
                    </div>
                    <div class="control-panel">
                        <a href="#" :disabled="active == 0" @click.stop.prevent='prev'><i
                                    class="glyphicon glyphicon-chevron-left"></i></a>
                        <a href="#" @click.stop.prevent='repeat' :class="repeatOne >= 0? 'active' : '' ">重复单句</a>
                        <a href="#" :disabled="active == pointsCount - 1 " @click.stop.prevent='next'><i
                                    class="glyphicon glyphicon-chevron-right"></i></a>

                        <a href="#" @click.stop.prevent='toggleFr' :class="fr ? 'active' : ''">法</a>
                        <a href="#" @click.stop.prevent='toggleZh' :class="zh ? 'active' : ''">中</a>
                    </div>
                </div>


                <div class="col-md-5">
                    @if($canRead)
                        <div class="video-content grey">
                            <table>
                                <tbody>
                                <tr v-for="no in pointsCount">
                                    <td class='width40'>
                                        <a href='#@{{ $index }}' @click.stop.prevent='seekTo($index)' class='seek-btn'
                                           :class="played.indexOf($index) > -1 > 'active' : ''"></a>
                                    </td>
                                    <td>
                                    <td>
                                        <p>@{{{ linesFr[no] }}}</p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        {{--<div class="notes">--}}
                        {{--<div class="form-group">--}}
                        {{--<div class="input-group">--}}
                        {{--<input type="text" class="note-input form-control" placeholder="@lang('labels.addNote')" v-model="newNote">--}}
                        {{--<a href="#" @click.stop.prevent="saveNote" class="input-group-addon navbar-note-btn"><span class="glyphicon glyphicon glyphicon-plus"></span></a>--}}
                        {{--</div>--}}
                        {{--</div>--}}

                        {{--<table  class="table table-striped">--}}
                        {{--<tr v-for="note in notes">--}}
                        {{--<td>@{{ $index }}</td>--}}
                        {{--<td>@{{{note}}}</td> </tr>--}}
                        {{--</table>--}}
                        {{--</div>--}}
                    @else
                        @include('blockContent')
                    @endif
                </div>
            </div>
        </div>

        <div class="container">
            <h1 class="video-title">{{ $readable ->title}}</h1>
            <div class="video-author">
                @lang('labels.publishAt'){{$readable->created_at}},
                <a href="{{url('users/' . $readable->listener->id)}}">{{$readable->listener->name}}</a>@lang('labels.listen')
                ,
                <a href="{{url('users/' . $readable->translator->id)}}">{{$readable->translator->name}}</a>@lang('labels.translate')
                ,
                <a href="{{url('users/' . $readable->verifier->id)}}">{{$readable->verifier->name}}</a>@lang('labels.verifier')
            </div>
        </div>
    </div>

    <div class="container video-show">
        @if($canRead)
            <h3><i class="glyphicon glyphicon-film"></i>@lang('labels.videoDesc')</h3>
            <div class="row">
                <div class="col-md-8">
                    {!! $readable->parsed_desc !!}
                </div>
                <div class="col-md-4">

                </div>
            </div>
        @endif

        <div class="Header"></div>
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
                 data-description="@lang('labels.shareTo')" data-image="{{$readable->avatar}}">
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
        @include('components.readableList')

        @include('components.comments')
    </div>
    <div id='goTop'></div>
@endsection

@section('otherjs')
    @if($youtube)
        <script>
        var tag = document.createElement('script');

        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        </script>
    @else
        <script src="http://vjs.zencdn.net/5.10.7/video.js"></script>
    @endif
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>

    <script>
        $('#goTop').goTop();

        $('img.Card-image').lazyload();

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

            @include('components.dict')
            $(".video-content span").click(activePopover)
        });

        var player;
        var currentTime;

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
                favWord: 'glyphicon-heart-empty',
                played: [],    //  保存已经播放过的橘子
                active: -1,
                currentFr: "@lang('labels.startToSearchWord')",
                currentZh: "",
                favorite: '{{$like ? 'is-active' : ''}}',
                isFavorite: '{{$like}}',
                collect: '{{$collect ? 'is-active' : ''}}',
                isCollect: '{{$collect}}',
                lineActive: '',
                repeatOne: -1,  //>=0 则说明循环开启
                newNote: '',
                notes: []
            },

            ready() {
                var pointStr = '{{$readable->points}}';
                this.points = pointStr.split(',');
                this.pointsCount = this.points.length;

                @if($canRead)
                        this.linesFr = "{!!$readable->parsed_content!!}".split('||');
                        this.linesZh = "{!!$readable->parsed_content_zh!!}".split('||');
                @endif
            },

            methods: {
                seekTo(no) {
                    var time = this.points[no];
                    player.currentTime(time);
                    @if($youtube)
                    player.playVideo();
                    @else
                    player.play();
                    @endif
                },

                favoriteEvent() {
                    this.$http.post('{{url("/" . $type . "s/" . $readable->id . '/favorite')}}', function (response) {
                    }.bind(this));
                },

                collectEvent() {
                    this.$http.post('{{url("/". $type . "s/" . $readable->id . '/collect')}}', function (response) {
                    }.bind(this));
                },

                punchinEvent() {
                    var punchin = $('#punchin');

                    this.$http.post('{{url("/". $type . "s/" . $readable->id . '/punchin')}}', function (response) {
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

                timeupdate() {
                    var currentTime = player.currentTime();
                    for (var i = 0; i < this.points.length; i++) {
                        if (this.repeatOne >= 0) {   //repeatOne is open
                            if (currentTime >= this.points[this.repeatOne + 1]) {
                                player.currentTime(this.points[this.repeatOne]);
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
                    if (this.repeatOne >= 0) {
                        this.repeatOne = -1;
                    } else {
                        this.repeatOne = this.active;
                    }
                },

                toggleFr() {
                    this.fr = !this.fr;
                },

                toggleZh() {
                    this.zh = !this.zh;
                },
                saveNote() {
                    this.notes.push(this.newNote);
                    this.newNote = '';
                }
            }
        });

        @if($youtube)
        function onYouTubeIframeAPIReady() {
            player = new YT.Player('video-placeholder', {
                videoId: "{{$readable->originSrc}}",
                playerVars: {
                    color: 'white',
                    autoplay: 1,
                    showinfo: 0,
                    rel: 0
                },
                events: {
                    onReady: initialize
                }
            });

            function initialize() {
                setInterval(vm.timeupdate, 1000);
            }

            player.currentTime = function (time) {
                if (time == undefined || time == '') {
                    return player.getCurrentTime();
                } else {
                    player.seekTo(time);
                }
            };

        }

        @else
        videojs("my_video").ready(function () {
            player = this;

            player.on('timeupdate', vm.timeupdate);
            player.play();
            $('#my_video').show();
        });
        @endif

        $(document).keydown(function (e) {
            switch (e.which) {
                case 32:    //空格，作为播放和停止的快捷键
                    var tag = e.target.tagName.toLowerCase();
                    if (tag != 'input' && tag != 'textarea') {
                        @if($youtube)
                        if (player.getPlayerState() == 2) {
                            player.playVideo();
                        } else {
                            player.pauseVideo();
                        }
                        @else
                        if (player.paused()) {
                            player.play();
                        } else {
                            player.pause();
                        }
                        @endif
                        e.preventDefault();
                    }
            }
        });
    </script>
@endsection

