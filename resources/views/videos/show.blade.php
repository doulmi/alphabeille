@extends('app')

@section('title'){{ $readable->title }}@endsection

@section('header')
    <meta name="description" content="{{$readable->title}}">
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
                    <div class="video-state">
                        <a href="{{url('videos/level/' . $readable->level)}}">
                            <span class="label label-success {{$readable->level}}">@lang('labels.' . $readable->level)</span>
                        </a>
                    <span class="">
                        <i class="glyphicon glyphicon-headphones"></i>
                        <span class="g-font">{{ $readable->views }}</span>
                    </span>
                    </div>
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
                                <div class="loading">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>

                                <div class="after-loading">
                                    <p v-show="fr">@{{{currentFr}}}</p>
                                    <p v-show="zh">@{{{currentZh}}}</p>
                                </div>
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
                        <ul id="tabs" class="nav nav-tabs hidden-xs" data-tabs="tabs">
                            <li class="active"><a href="#subtitles" data-toggle="tab">@lang('labels.subtitles')</a></li>
                            <li><a href="#notes" data-toggle="tab">@lang('labels.notes')</a></li>
                        </ul>

                        <div class="video-content grey" id='subPanel'>
                            <div class="loading">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                            <div class="after-loading">
                                <div id="my-tab-content" class="tab-content">
                                    <div id="notes" class="tab-pane">
                                        <div v-if="notes.length == 0">@lang('labels.noNoteYet')</div>
                                        <table class="note-table" v-else>
                                            <tbody>
                                            <tr v-for="note in notes">
                                                <td class="width40">
                                                    <a href='#@{{note.point}}'
                                                       @click.stop.prevent='seekToTime(note.point)' class='seek-btn'
                                                       :class="played.indexOf($index) > -1 > 'active' : ''"></a>
                                                </td>
                                                <td><p id="note-@{{ note.id }}" class="contenteditable tooltips-bottom" data-tooltips="@lang('labels.clickToEdit')" contenteditable="true" >@{{{ note.content }}}</p></td>
                                                <td><p><a href="" @click.stop.prevent="deleteNote($index, note.id)"
                                                                    class="close vertical-center tooltips-left" data-tooltips="@lang('labels.delete')">x</a></p></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="subtitles" class="tab-pane active">
                                        <table>
                                            <tbody>
                                            <tr v-for="no in pointsCount">
                                                <td class='width40'>
                                                    <a href='#@{{ $index }}' @click.stop.prevent='seekTo($index)'
                                                       class='seek-btn'
                                                       :class="played.indexOf($index) > -1 > 'active' : ''"></a>
                                                </td>
                                                <td>
                                                    <p>@{{{ linesFr[no] }}}</p>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
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

            <div class="share-component share-panel" data-sites="wechat, weibo ,facebook"
                 data-description="@lang('labels.shareTo')" data-image="{{$readable->avatar}}">
                @lang('labels.share'):
            </div>

            <ul id="tabs" class="nav nav-tabs videoPart">
                <li class="active"><a href="javascript:;">@lang('labels.videoDesc')</a></li>
                <li><a href="javascript:;" onclick="scrollToTag('sugguest')">@lang('labels.suggestVideos')</a></li>
                <li><a href="javascript:;" onclick="scrollToTag('disqus_thread')" >@lang('labels.comments')</a></li>
            </ul>
        </div>
    </div>

    <div class="container video-show">
        @if($canRead)
            <h3 ><i class="glyphicon glyphicon-film"></i>@lang('labels.videoDesc')</h3>
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

        @endif
    </div>

    <div class="Card-Collection" id="sugguest">
        {{--推荐部分--}}
        <div class="Header"></div>
        <h2 class="Heading-Fancy row">
            <span class='title black'>{{ trans('labels.suggestVideos')}}</span>
        </h2>
        @include('components.readableList')
        @include('components.comments')
    </div>
    <div class="Header"></div>
    <div class="login-dialog" id="login-container" v-show="showLoginPanel" transition="bounce">
        <p>@lang('labels.loginToUse')</p>
        <div class="login-footer center">
            <a href="{{url('login')}}" class="btn login-btn">@lang('labels.login')</a>
            <a href="" class="btn btn-default" @click.stop.prevent="closeLogin">@lang('labels.close')</a>
        </div>
    </div>

    <div class="note-container" id="note-container" v-show="showNotePanel" transition="bounce">
        <textarea data-provide="markdown" rows="5" cols="50" id="note-content" title="Note"
                  placeholder="@lang('labels.writeNote')" v-model="newNote"></textarea>
        <div class="note-footer pull-right">
            <a href="" class="btn login-btn" id="saveNoteBtn" @click.stop.prevent="saveNote">@lang('labels.save')</a>
            <a href="" class="btn btn-default" @click.stop.prevent="closeNote">@lang('labels.close')</a>
        </div>
    </div>
    <div class="fixed-action-btn hidden-xs">
        <a class="btn-floating waves-effect waves-light red " @click="showNoteDialog">
        <img src="data:image/svg+xml;base64,PHN2ZyBmaWxsPSIjRkZGRkZGIiBoZWlnaHQ9IjI0IiB2aWV3Qm94PSIwIDAgMjQgMjQiIHdpZHR
oPSIyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICAgIDxwYXRoIGQ9Ik
0xOSAxM2gtNnY2aC0ydi02SDV2LTJoNlY1aDJ2Nmg2djJ6Ii8+CiAgICA8cGF0aCBkPSJNMCAwa
DI0djI0SDB6IiBmaWxsPSJub25lIi8+Cjwvc3ZnPg==">
        </a>
    </div>
    <div id='goTop'></div>
    <div class="Header"></div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>
    <script>
        $('#goTop').goTop();

        function scrollToTag(tag) {
            $('html, body').animate({
                scrollTop: $("#" + tag).offset().top
            }, 2000);
        }

        var subPanel = $('#subPanel');
        var videoPanel = $('#videoPanel');

        $('img.Card-image').lazyload();

        $(window).resize(function () {
            subPanel.height(videoPanel.height() - 52);
        });

        $(function () {
            $('#tabs').tab();

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

            @if(Auth::guest())
            $(".video-content span").click(function () {
                vm.showLoginDialog();
            });
            @else
            $(".video-content span").click(activePopover);
            @endif
            if (videoPanel.height() >= 300) {
                subPanel.height(videoPanel.height() - 52);
            }

            $('.contenteditable').blur(function () {
                var id = $(this).attr('id').substr(5);
                var content = $(this).html();
                vm.updateNote(id, content);
            });
        });

        var player;
        var currentTime;
        var noteContainer = $('#note-container');
        var loginContainer = $('#login-container');
        var saveNoteBtn = $('#saveNoteBtn');

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
                notes: [],
                showNotePanel: false,
                showLoginPanel: false
            },

            ready: function () {
                var pointStr = '{{$readable->points}}';
                this.points = pointStr.split(',');
                this.pointsCount = this.points.length;

                @if($canRead)
                        this.linesFr = "{!!$readable->parsed_content!!}".split('||');
                this.linesZh = "{!!$readable->parsed_content_zh!!}".split('||');
                @endif

                this.notes = JSON.parse('{!! $notes !!}');

                $('.after-loading').fadeIn();
                $('.loading').hide();
            },

            methods: {
                seekTo(no) {
                    var time = this.points[no];
                    this.seekToTime(time);
                },

                seekToTime(time) {
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
                    if(this.newNote.trim() == '') {
                        toastr.warning("@lang('labels.emptyContent')");
                    }
                    saveNoteBtn.attr('disabled', 'disabled');
                    var note = {
                        id: '{{$readable->id}}',
                        content: this.newNote.replace('\n', '<br/>'),
                        point: player.currentTime()
                    };

                    this.$http.post("{{url('/'. $type .'Notes')}}", note, function (data) {
                        this.notes.push(note);
                        this.newNote = '';
                        toastr.success("@lang('labels.noteSuccess')");
                        this.showNotePanel = false;
                        saveNoteBtn.removeAttr('disabled');
                    }.bind(this));
                },

                closeNote() {
                    this.newNote = '';
                    this.showNotePanel = false;
                },
                showNoteDialog() {
                    @if(Auth::guest())
                        this.showLoginDialog();
                    @else
                        this.showNotePanel = !this.showNotePanel;
                    @endif
                },

                closeLogin() {
                    this.showLoginPanel = false;
                },

                showLoginDialog() {
                    this.showLoginPanel = true;
                },

                updateNote(id, content) {
                    var note = {
                        id: id,
                        content: content
                    };
                    this.$http.put("{{url('notes')}}", note, function (data) {
                    }.bind(this));
                },

                deleteNote(index, id) {
                    var note = {id: id};
                    this.notes.splice(index, 1);
                    this.$http.delete("{{url('/notes')}}", note, function (data) {}.bind(this));
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
                    if (tag != 'input' && tag != 'textarea' && tag != 'p') {
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

