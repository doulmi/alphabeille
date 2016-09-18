
function scrollToTag(tag) {
    $('html, body').animate({
        scrollTop: $("#" + tag).offset().top - 50
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
        words : [],
        showNotePanel: false,
        showLoginPanel: false,
        originPlayerState : ''
    },

    ready: function () {
        var pointStr = '{{$readable->points}}';
        this.points = pointStr.split(',');
        this.pointsCount = this.points.length;

                @if($canRead)
            this.linesFr = "{!!$readable->parsed_content!!}".split('||');
        this.linesZh = "{!!$readable->parsed_content_zh!!}".split('||');
                @endif

        try {
            this.notes = JSON.parse('{!! $notes !!}');
            this.words = JSON.parse('{!! $words !!}');
        }catch (err) {console.log('parse error')}

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
            if (this.newNote.trim() == '') {
                toastr.warning("@lang('labels.emptyContent')");
                return;
            }
            player.play();
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
            player.play();
        },

        showNoteDialog() {
            player.pause();
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
            this.$http.delete("{{url('/notes')}}", note, function (data) {
            }.bind(this));
        },

        deleteWord(index, id) {
            var word = {id: id};
            this.words.splice(index, 1);
            this.$http.delete("{{url('/wordFavorites')}}", word, function (data) {}.bind(this));
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

        player.pause = function() {
            player.pauseVideo();
        };
        player.play = function() {
            player.playVideo();
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
//# sourceMappingURL=video.show.js.map
