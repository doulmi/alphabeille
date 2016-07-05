@extends('app')

@section('title')
    {{ $talkshow->title }}
@endsection

@section('header')
    <meta name="description" content="{{$talkshow->description}}">
    <meta name="Keywords" content="{{ $talkshow->keywords }}">
@endsection

@section('othercss')
    <link rel="stylesheet" href="/css/share.min.css">
@endsection

@section('content')
    <meta property="og:title" content="{{$talkshow->title}}" />
    <meta property="og:image" content="{{$talkshow->avatar}}" />

    <link rel="stylesheet" href="/css/audioplayer.css"/>
    <div class="body grey">

        <?php $canRead = $talkshow->free || (!Auth::guest() && Auth::user()->level() > 1) ?>
        <div class="Header"></div>

        <div class="lesson-content">
            <br/>
            <h1 class="mar-t-z center">
                {{ $talkshow->title }}
            </h1>

            <div class="center">
                Par <a href="{{url('/')}}">alpha-beille.com</a> | {{$talkshow->created_at}}
            </div>
            <br/>

            @if($canRead)
                <div class="playerPanel">
                    <audio id='audio' preload="auto" controls hidden>
                        <source src="{{$talkshow->audio_url}}"/>
                    </audio>
                </div>

                <div class="shortcut">
                    @lang('labels.shortcut.pausePlay') : <span
                            class="label label-default">@lang('labels.shortcut.space')</span>&nbsp;|&nbsp;
                    @lang('labels.shortcut.advance') : <span class="label label-default">→</span>&nbsp;|&nbsp;
                    @lang('labels.shortcut.back') : <span class="label label-default">←</span>&nbsp;|&nbsp;
                    @lang('labels.shortcut.volumeUp') :
                    <span class="label label-default">Ctrl</span>
                    <span class="label label-default">↑</span>&nbsp;|&nbsp;

                    @lang('labels.shortcut.volumeDown') :
                    <span class="label label-default">Ctrl</span>
                    <span class="label label-default">↓</span>
                </div>
            @else
                @include('blockContent')
            @endif
            <br/>
            <br/>

            @if($talkshow->free || (!Auth::guest() && Auth::user()->level() > 1))
                <div class='markdown-content'>
                    {!! $content !!}
                </div>
            @endif
            @if(!Auth::guest())
                <div class="center">
                    <a href="#" data-tooltips="@lang('labels.favorite')" class="favorite-circle"
                       @click.stop.prevent="favoriteEvent">
                        <i class="favorite glyphicon " v-bind:class="favorite"></i>
                    </a>

                    @if(!$punchin)
                        <a id="punchinlink" href="#" data-tooltips="@lang('labels.punchin')" class="favorite-circle"
                           @click.stop.prevent="punchinEvent">
                            <i class="favorite glyphicon glyphicon-ok"></i>
                        </a>
                    @endif

                    <a href="#" data-tooltips="@lang('labels.collect')" class="favorite-circle"
                       @click.stop.prevent="collectEvent">
                        <i class="favorite glyphicon" v-bind:class="collect"></i>
                    </a>

                </div>
                <div class="share-component share-panel" data-sites="wechat, weibo ,facebook" data-description="@lang('labels.shareTo')" data-image="{{$talkshow->avatar}}">
                    @lang('labels.share'):
                </div>
            @endif
            <div class="Header"></div>
        </div>
            <div class="Header"></div>
            <h2 class="Heading-Fancy row">
                <span class='title'>{{ trans('labels.suggestTalkshows')}}</span>
            </h2>
            <div class="Card-Collection">
                <div class="row">
                    @include('talkshows.talkshowsList')
                </div>
            <div class="Header"></div>

            <div id="disqus_thread">
                <h1 class="black">@lang('labels.comments')</h1>
                @if(Auth::guest())
                    <div class="">
                        <div class="Header"></div>
                        <div class="center">
                            <a href="{{url('login')}}">@lang('labels.login')</a>
                            @lang('labels.loginToReply')
                        </div>
                        <div class="Header"></div>
                    </div>
                @else
                    <div class="media ">
                        <div class="media-left">
                            <a href="#">
                                <img src="{{Auth::user()->avatar}}" alt="64x64" class="img-circle media-object"
                                     width="64px"
                                     height="64px">
                            </a>
                        </div>

                        <div class="media-body">
                            <h4 class="media-heading">
                            </h4>
                            <form v-on:submit="onPostComment" method="POST"
                                  id="replyForm">
                                {{csrf_field()}}

                                @include('UEditor::head')
                                        <!-- 加载编辑器的容器 -->
                                <script id="container" style="width: 100%; height : 100px" name="content"
                                        v-model="newPost.body"
                                        type="text/plain" placeholder="@lang('labels.addComment')"></script>
                                <input type="hidden" name="talkshow_id" value="{{$talkshow->id}}">
                                <button type="submit" class="pull-right btn btn-submit">@lang('labels.reply')</button>
                            </form>
                        </div>
                    </div>
                @endif

                {{--<center v-if="!comment_visible">--}}
                {{--<div id="loader">--}}
                {{--<img src="/css/svg-loaders/rings.svg" width='100px' alt=""/>--}}
                {{--</div>--}}
                {{--</center>--}}
                <div class="comments" v-if="comment_visible">
                    <ul id="comments">
                        <li v-for="comment in comments" class="comment">
                            <div class="media">
                                <a class="media-left">
                                    <img src="@{{comment.avatar}}" alt="64x64" class="img-circle media-object"
                                         width="64px"
                                         height="64px">
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
                                                onclick="reply(@{{comment.userId}},'@{{ comment.name}}')">@lang('labels.reply')</button>
                                    </div>
                                @endif
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="Header"></div>
            <div class="Header"></div>
        </div>
            <div id='goTop'></div>
    </div>

    @include('smallBeach')
@endsection

@section('otherjs')
    <script src="/js/audio.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.0/vue-resource.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>
    <script src="/js/social-share.min.js"></script>

    <script>
        $('img.Card-image').lazyload();

        $('#goTop').goTop();

        toastr.options = {
            "positionClass": "toast-top-center"
        };

        var isIE = function (ver) {
            var b = document.createElement('b');
            b.innerHTML = '<!--[if IE ' + ver + ']><i></i><![endif]-->';
            return b.getElementsByTagName('i').length === 1
        };

        if (isIE(6) || isIE(7) || isIE(8)) {
        }

        $('#goTop').goTop();
        function reply(userId, userName) {
            window.location.href = "#replyForm";
            ue.setContent('<a href="/users/' + userId + '">@' + userName + '</a>', false);
        }

        function removeHTML(strText) {
            var regEx = /<[^>]*>/g;
            return strText.replace(regEx, "");
        }

        Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
        new Vue({
            el: 'body',

            ready: function () {
                this.$http.get('{{url("/talkshowComments/" . $talkshow->id)}}', function (response) {
                    this.comments = response;
                    this.comment_visible = true;
                }.bind(this));
            },

            data: {
                comments: [],
                comment_visible: false,
                favorite: '{{$like ? 'glyphicon-heart' : 'glyphicon-heart-empty'}}',
                isFavorite: '{{$like}}',
                collect: '{{$collect ? 'glyphicon-star' : 'glyphicon-star-empty'}}',
                isCollect: '{{$collect}}',
                newComment: {
                    name: '{{Auth::user() ? Auth::user()->name : ''}}',
                    avatar: '{{Auth::user() ? Auth::user()->avatar : ''}}',
                    content: ''
                },

                newPost: {
                    talkshow_id: '{{$talkshow->id}}',
                    content: ''
                }
            },

            methods: {
                favoriteEvent() {
                    if (this.isFavorite) {
                        this.isFavorite = false;
                        this.favorite = 'glyphicon-heart-empty';
                    } else {
                        this.isFavorite = true;
                        this.favorite = 'glyphicon-heart';
                    }
                    this.$http.post('{{url("/talkshows/" . $talkshow->id . '/favorite')}}', function (response) {
                    }.bind(this));
                },

                collectEvent() {
                    if (this.isCollect) {
                        this.isCollect = false;
                        this.collect = 'glyphicon-star-empty'
                    } else {
                        this.isCollect = true;
                        this.collect = 'glyphicon-star';
                    }
                    this.$http.post('{{url("/talkshows/" . $talkshow->id . '/collect')}}', function (response) {
                    }.bind(this));
                },

                punchinEvent() {
                    var punchin = $('#punchin');
                    $('#punchinlink').hide();

                    this.$http.post('{{url("/talkshows/" . $talkshow->id . '/punchin')}}', function (response) {
                        punchin.html(parseInt(punchin.html()) + 1);
                        var series = response.series;
                        var isBreakup = response.break;
                        if(isBreakup) {

                            @if(!Auth::guest())
                            toastr.success('@lang('labels.punchinSuccess')' + '@lang('labels.breakup')' + '@lang('labels.continuePunchin', ['day' => Auth::user()->series + 1])' );
                            @endif
                        }
                    }.bind(this));
                },

                onPostComment: function (e) {
                    e.preventDefault();

                    var comment = this.newComment;
                    var post = this.newPost;
                    post.content = ue.getContent();

                    if (removeHTML(post.content).length < 10) {
                        toastr.error('@lang('labels.tooShortComment')');
                        return;
                    }
                    comment.content = post.content;

                    console.log(post.content);
                    this.$http.post('/talkshowComments', post, function (data) {
                        this.comments.unshift(comment);
                        console.log(data);

                        toastr.success('@lang('labels.feelFreeToComment')', '@lang('labels.commentSuccess')');
                        comment = {
                            name: '{{Auth::user() ? Auth::user()->name : ''}}',
                            avatar: '{{Auth::user() ? Auth::user()->avatar: ''}}',
                            body: ''
                        };
                        ue.setContent('');
                    })
                }
            }
        });

                @if(!Auth::guest())
        var ue = UE.getEditor('container', {
                    toolbars: [
                        ['fullscreen', 'source', 'undo', 'redo', '|', 'removeformat', 'formatmatch', 'selectall', 'cleardoc',],
                        ['bold', 'italic', 'underline', 'fontborder', 'strikethrough', '|', 'insertimage', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist']
                    ],
                    focus: true,
                    elementPathEnabled: false,
                    maximumWords: 1000
                });
        ue.ready(function () {
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
        });
        @endif
    </script>
@endsection