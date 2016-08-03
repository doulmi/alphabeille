@extends('app')

@section('title'){{ $readable->title }}@endsection

@section('header')
    <meta name="description" content="{{$readable->description}}">
    <meta name="Keywords" content="{{ $readable->keywords }}">
@endsection

@section('content')
    <div class="grey">
        <div class="container video-show">
            <?php $canRead = $readable->free || (!Auth::guest() && Auth::user()->level() > 1) ?>
            <div class="Header"></div>
            <h1 class="center">
                {!! $readable ->title !!}
            </h1>

            <div class="author">
                Par <a href="{{url('/')}}">alpha-beille.com</a> | {{$readable->created_at}}
            </div>
            <div class="row">
                <div class="col-md-7">
                    <video id="my_video" class="video-js vjs-default-skin"
                           controls preload
                           data-setup='{ "aspectRatio":"1920:1080", "playbackRates": [0.5, 0.75, 1, 1.25, 1.5, 2] }'>
                        <source src="{{$readable->video_url}}" type='video/mp4'>
                    </video>
                    <div class="subtitle">
                        <div class="center">
                            @if($canRead)
                                <p v-show="fr">@{{{currentFr}}}</p>
                                <p v-show="zh">@{{{currentZh}}}</p>
                            @endif
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
                </div>

                <div class="col-md-5">
                    @if($canRead)
                        <div class="video-content grey">
                            <table>
                                <tbody>
                                <tr v-for="line in linesFr">
                                    <td class='width40 '><a href='#@{{ $index }}'
                                                            @click.stop.prevent='seekTo($index)'
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
                    @else
                        @include('blockContent')
                    @endif
                </div>
            </div>

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

        <div class="container">
            {{--推荐部分--}}
            {{--<div class="Header"></div>--}}
            {{--<h2 class="Heading-Fancy row">--}}
            {{--<span class='title black'>{{ trans('labels.suggestVideos')}}</span>--}}
            {{--</h2>--}}
            {{--@include('utils.readableList')--}}

            @include('components.comments')
        </div>
        <div id='goTop'></div>
        @endsection

        @section('otherjs')
            <script src="http://vjs.zencdn.net/5.10.7/video.js"></script>
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

                    $('body').click(function (event) {
                        var target = $(event.target);       // 判断自己当前点击的内容
                        if (!target.hasClass('popover')
                                && !target.hasClass('pop')
                                && !target.hasClass('popover-content')
                                && !target.hasClass('popover-title')
                                && !target.hasClass('arrow')) {
                            $('.popover').popover('hide');      // 当点击body的非弹出框相关的内容的时候，关闭所有popover
                        }
                    });
                    var spans = $(".video-content span");

                    var closeBtn = '<button type="button" id="close" class="close" onclick="$(\'.popver\').popover(\'hide\');">&times;</button>';
                    var collect = '<a href="#"><i class="glyphicon " :class="favWord"></i></a>';

//                    var collect = '<a href="#" ><i class="svg-icon svg-icon-heart"></i></a>';
                    //POPOVER
                    spans.click(function () {
                        var word = $(this).html().trim().toLowerCase();
                        $('.popover').popover('hide');          // 当点击一个按钮的时候把其他的所有内容先关闭。

                        var result = localStorage.getItem('dict:fr:' + word);
                        if (result && result != '') {
                            //有缓存的情况下
                            $(this).popover({
                                placement: 'bottom', trigger: 'focus', delay: {show: 10, hide: 100}, html: true,
                                title: function () {
                                    return collect + $(this).html() + closeBtn;
                                },
                                content: function () {
                                    return result; // 把content变成html
                                }
                            });
                            $(this).popover('toggle');
                        } else {
                            //无缓存则从服务器获取信息
                            $(this).popover({
                                placement: 'bottom', trigger: 'focus', delay: {show: 10, hide: 100}, html: true,
                                title: function () {
                                    return collect + $(this).html() + closeBtn;
                                },
                                content: function () {
                                    return "@lang('labels.loading')";
                                }
                            });
                            $(this).popover('toggle');          // 然后只把自己打开。
                            $.get("{{url('api/words')}}" + "/" + word, function (response) {
                                $(".popover-content").html(response['msg']);
                                if(response['status'] == 200) {
                                    localStorage.setItem('dict:fr:' + word, response['msg']);
                                    if(response['isFav']) {

                                    }
                                }
                            });
                        }
                    });
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
                        favWord : 'glyphicon-heart-empty',
                        played: [],    //  保存已经播放过的橘子
                        active: -1,
                        currentFr: "@lang('startToSearchWord')",
                        currentZh: "",
                        favorite: '{{$like ? 'is-active' : ''}}',
                        isFavorite: '{{$like}}',
                        collect: '{{$collect ? 'is-active' : ''}}',
                        isCollect: '{{$collect}}',
                        lineActive: '',
                        repeatOne: -1  //>=0 则说明循环开启
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
                            if(this.repeatOne >= 0) {
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

