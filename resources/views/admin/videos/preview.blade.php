@extends('app')

@section('title'){{ $readable->title }}@endsection

@section('header')
    <meta name="description" content="{{$readable->description}}">
    <meta name="Keywords" content="{{ $readable->keywords }}">
@endsection

@section('content')
    <div class="container-fluid grey">
        <div class="Header"></div>
        <?php $canRead = $readable->free || (Auth::user() && Auth::user()->level() > 1) ?>
        <div class="container">
            <div class="row video-show">
                <div class="col-md-7">
                    <a href="{{url('videos/level/' . $readable->level)}}"><span
                                class="label label-success {{$readable->level}}">@lang('labels.' . $readable->level)</span></a>
                    <span class="">
                        <i class="glyphicon glyphicon-headphones"></i>
                    </span>
                    <div class="video-container">
                        <div id="video-placeholder"></div>
                    </div>
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
            <h1 class="video-title">{{ $readable->title}}</h1>
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
    </div>
@endsection

@section('otherjs')
    <script src="https://www.youtube.com/iframe_api"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>

    <script>
        $('#goTop').goTop();

        $('img.Card-image').lazyload();

        $(function () {
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
                count : 0,
                fr: true,
                zh: true,
                pointsCount: 0,
                favWord: 'glyphicon-heart-empty',
                played: [],    //  保存已经播放过的橘子
                active: -1,
                currentFr: "@lang('labels.startToSearchWord')",
                currentZh: "",
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
                console.log(this.linesFr);
                @endif
            },

            methods: {
                seekTo(no) {
                    var time = this.points[no];
                    player.currentTime(time);
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

        function onYouTubeIframeAPIReady() {
            player = new YT.Player('video-placeholder', {
                videoId: "{{$readable->originSrc}}",
                playerVars: {
                    color: 'white'
                },
                events: {
                    onReady: initialize
                }
            });

            function initialize(){
                setInterval(vm.timeupdate, 1000);
            }

            player.currentTime = function (time) {
                if (time == undefined || time == '') {
                    return player.getCurrentTime();
                } else {
                    player.seekTo(time);
                }
            }
        }

        $(document).keydown(function (e) {
            switch (e.which) {
                case 32:    //空格，作为播放和停止的快捷键
                    var tag = e.target.tagName.toLowerCase();
                    if (tag != 'input' && tag != 'textarea') {
                        if (player.paused()) {
                            player.play();
                        } else {
                            player.pause();
                        }
                        e.preventDefault();
                    }
            }
        });
    </script>
@endsection

