@extends('app')

@section('title'){{ $readable->title }}@endsection

<style>
    .btn-toolbar .btn-group {
        display: none;
    }
</style>
@section('content')
    <div class="container-fluid grey">
        <div class="Header"></div>
        <div class="container">
            <form action="" method="post" id="form">
                {!! csrf_field() !!}
                <div class="row video-show">
                    <div class="col-md-5">
                        @if($youtube)
                            <div class="video-container">
                                <div id="video-placeholder"></div>
                            </div>
                        @else
                            <video id="my_video" class="video-js vjs-default-skin"
                                   controls preload
                                   data-setup='{ "aspectRatio":"1920:1080", "playbackRates": [0.5, 0.75, 1, 1.25, 1.5, 2] }'>
                                <source src="{{$readable->video_url}}" type='video/mp4'>
                            </video>
                        @endif

                        <div class="video-content grey translate-content">
                            <div class="loading">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                            <div class="after-loading">
                                <table>
                                    <tbody id="subs">
                                    <tr v-for="no in pointsCount">
                                        <td class='width40'>
                                            <a href='#@{{ $index }}' @click.stop.prevent='seekTo($index)'
                                               class='seek-btn'
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
                        </div>
                    </div>

                    <div class="col-md-7">
                   <textarea class="name-input form-control" rows="25" id="content" data-provide="markdown"
                             name="content">{{ $task->content }}</textarea>
                    </div>
                </div>

                <div class="row" style="margin-top : 20px;">
                    @if(Auth::user()->isAdmin())
                        <a href="#" onclick="submitForm('submitForce')" style="margin-left : 10px"
                           class="btn btn-success pull-right">@lang('labels.submitForce')</a>
                    @endif

                    @if(Auth::user()->can('videos.listen'))
                        <a href="#" onclick="submitForm('submitFr')" style="margin-left : 10px"
                           class="btn btn-success pull-right">@lang('labels.submitFr')</a>
                    @endif

                    @if(Auth::user()->can('videos.translate'))
                        <a href="#" onclick="submitForm('submit')" style="margin-left : 10px;"
                           @endif
                           class="btn btn-primary pull-right">@lang('labels.submit')</a>
                        <a href="#" onclick="submitForm('save')"
                           class="btn btn-success pull-right">@lang('labels.save')</a>
                </div>
            </form>
        </div>
    </div>

    <div class="container video-show">
        <div class="Header"></div>
    </div>

    <div class="Header"></div>
    <div class="Header"></div>
@endsection

@section('otherjs')
    @if($youtube)
        {{--<script src="https://www.youtube.com/iframe_api"></script>--}}
        <script>
            var tag = document.createElement('script');

            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        </script>
    @endif
    <script src="http://vjs.zencdn.net/5.10.7/video.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>

    <script>
        function submitForm(type) {
            var form = $('#form');
            form.attr('action', "{{url('translator/tasks/' . $task->id )}}" + "/" + type);
            form.submit();
        }

        $('#goTop').goTop();

        $('img.Card-image').lazyload();

        var content = $('#content');
        var change = false;

        function autoSave() {
            if (change) {
                $.post("{{url('translator/tasks/' . $task->id . '/autoSave')}}", {
                    content: content.val(),
                    _token: "{{csrf_token()}}"
                }, function (response) {
                    if (response['state'] == 200) {
                        toastr.success("@lang('labels.autoSaveSuccess')");
                        change = false;
                    } else {
                        toastr.error("@lang('labels.autoSaveFailed')");
                    }
                });
            }
        }

        $(function () {
            @include('components.dict')
            $(".video-content span").click(activePopover);

            content.keyup(function () {
                change = true;
            });

            //自动保存功能
            setInterval(function () {
                autoSave();

            }, 1000 * 30);
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
                var pointStr = '{{$readable->points}}';
                this.points = pointStr.split(',');
                this.pointsCount = this.points.length;

                this.linesFr = "{!!$readable->parsed_content!!}".split('||');
                this.linesZh = "{!!$readable->parsed_content_zh!!}".split('||');

                $('.after-loading').fadeIn();
                $('.loading').hide();
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
                player.currentTime = function (time) {
                    if (time == undefined || time == '') {
                        return player.getCurrentTime();
                    } else {
                        player.seekTo(time);
                    }
                }
            }
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

