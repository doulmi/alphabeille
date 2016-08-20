@extends('app')

@section('title'){{ $video->title }}@endsection

@section('header')
    <meta name="description" content="{{$video->description}}">
    <meta name="Keywords" content="{{ $video->keywords }}">
@endsection

@section('content')
    <div class="container-fluid grey">
        <div class="Header"></div>
            <form role="form" id="pointForm" action="{{url('admin/videos/' . $video->id . '/points')}}" method="POST">
                {!! csrf_field() !!}
                <div class="row video-show">
                    <div class="col-md-5">
                        <div class="video-container">
                            <div id="video-placeholder"></div>
                        </div>
                        <div class="subtitle">
                            <div class="center">
                                <p v-show="fr">@{{{currentFr}}}</p>
                                <p v-show="zh">@{{{currentZh}}}</p>
                            </div>
                            <div class="control-panel">
                                <a href="#" :disabled="active == 0" @click.stop.prevent='prev'><i
                                            class="glyphicon glyphicon-chevron-left"></i></a>
                                <a href="#" @click.stop.prevent='repeat'
                                   :class="repeatOne >= 0? 'active' : '' ">重复单句</a>
                                <a href="#" :disabled="active == pointsCount - 1 " @click.stop.prevent='next'><i
                                            class="glyphicon glyphicon-chevron-right"></i></a>

                                <a href="#" @click.stop.prevent='toggleFr' :class="fr ? 'active' : ''">法</a>
                                <a href="#" @click.stop.prevent='toggleZh' :class="zh ? 'active' : ''">中</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="points-panel">
                            {{--<input type="text" id="points" v-bind:value="points" data-role="tagsinput">--}}
                            <table class="table">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>#</th>
                                    <th>Time</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="point in points">
                                    <td class='width40 '><a href='#@{{ $index }}' @click.stop.prevent='seekTo($index)'
                                                            class='seek-btn'
                                                            :class="played.indexOf($index) > -1 > 'active' : ''"></a>
                                    </td>
                                    <th scope="row">@{{ $index }}</th>
                                    <td class="changeable" id="row-@{{ $index }}" contenteditable="true">@{{ point }}</td>
                                    <td><a href="#" @click="deletePoint($index)">Delete</a></td>
                                    <td> <p>@{{{linesFr[$index]}}}</p> </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div style="margin-top:30px;">
                            <a class="btn btn-lg btn-success " @click.stop.prev="addCurrentPoint">Cut</a>
                            <a onclick="submit()" class="btn btn-lg btn-primary pull-right" >提交</a>
                        </div>
                        <input type="hidden" name="points" value="" id="points">
                    </div>
                </div>
            </form>
        </div>
        <h1 class="video-title">{{ $video->title}}</h1>

    {{--<div class="container video-show">--}}
        {{--<h3><i class="glyphicon glyphicon-film"></i>@lang('labels.videoDesc')</h3>--}}
        {{--<div class="row">--}}
            {{--<div class="col-md-8">--}}
                {{--{!! $video->parsed_desc !!}--}}
            {{--</div>--}}
            {{--<div class="col-md-4">--}}

            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="Header"></div>--}}
    {{--</div>--}}
@endsection

@section('otherjs')
    <script src="https://www.youtube.com/iframe_api"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>

    <script>
        $('#goTop').goTop();

        $('img.Card-image').lazyload();

//        $(function () {
{{--            @include('components.dict')--}}
//            $(".video-content span").click(activePopover)
//        });

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
                count: 0,
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
                var pointStr = '{{$video->points}}';
                this.points = pointStr.split(',');
                this.pointsCount = this.points.length;

                this.linesFr = "{!!$video->parsed_content!!}".split('||');
                this.linesZh = "{!!$video->parsed_content_zh!!}".split('||');
            },

            methods: {
                seekTo(no) {
                    var time = this.points[no];
                    player.currentTime(time);
                },

                addCurrentPoint() {
                    var current = Math.round(player.currentTime() * 100) / 100;
                    for(var i = 0; i < this.points.length; i++) {
                        if(current == this.points[i]) {
                            return;
                        }
                    }
                    this.points.push(current);
                    this.points.sort(function (a, b) {
                        return a - b;
                    });
                },

                deletePoint(no) {
                    this.points.splice(no, 1);
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
                videoId: "{{$video->originSrc}}",
                playerVars: {
                    color: 'white'
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

        $(function() {
            $('.changeable').blur(function() {
                var idx = $(this).attr('id').substr(4);
                var point = $(this).html();
                vm.points[idx] = point;
            });
        });

        function submit() {
            $('#points').val(vm.points);
            $('#pointForm').submit();
        }
    </script>
@endsection

