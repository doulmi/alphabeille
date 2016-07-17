@extends('app')

@section('title')
    {{ $minitalk->title }}
@endsection

@section('header')
    <meta name="description" content="{{$minitalk->description}}">
    <meta name="Keywords" content="{{ $minitalk->keywords }}">
@endsection

@section('content')
    <meta property="og:title" content="{{$minitalk->title}}"/>
    <meta property="og:image" content="{{$minitalk->avatar}}"/>

    <div class="body grey">
        <?php $canRead = $minitalk->free || (!Auth::guest() && Auth::user()->level() > 1) ?>
        <div class="Header"></div>

        <div class="lesson-content">
            <br/>
            <h1 class="mar-t-z center">
                {{ $minitalk->title }}
            </h1>

            <div class="center">
                Par <a href="{{url('/')}}">alpha-beille.com</a> | {{$minitalk->created_at}}
            </div>
            <br/>

            <div class="playerPanel">
                <audio id='audio' preload="auto" controls hidden>
                    <source src="{{$minitalk->audio_url}}"/>
                </audio>
            </div>

            <div class="shortcut hidden-xs">
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
            <br/>
            <br/>

            @if($canRead)
                <div class='markdown-content'>
                    {!! $content !!}
                </div>
            @endif
            <div class='markdown-content wechat-part'>
                {!! $wechat_part !!}

                <p>喜欢的话，可以关注我们的微信公众号，你不扫一下吗？</p>
                <p><img src="http://o9dnc9u2v.bkt.clouddn.com/qr-wechat.jpg" alt="Wehcat QR-AlphabeilleStudio" title="Wehcat QR-AlphabeilleStudio"></p>
            </div>

            @if(!Auth::guest())
                <div class="center">
                    <a href="#" data-tooltips="@lang('labels.favorite')" class="favorite-circle"
                       @click.stop.prevent="favoriteEvent">
                        <i class="favorite glyphicon " v-bind:class="favorite"></i>
                    </a>

                    <a href="#" data-tooltips="@lang('labels.collect')" class="favorite-circle"
                       @click.stop.prevent="collectEvent">
                        <i class="favorite glyphicon" v-bind:class="collect"></i>
                    </a>
                </div>

                <div class="share-component share-panel" data-sites="wechat, weibo ,facebook"
                     data-description="@lang('labels.shareTo')" data-image="{{$minitalk->avatar}}">
                    @lang('labels.share'):
                </div>
            @endif
            <div class="Header"></div>
        </div>
        <div class="Header"></div>

        <div class="Card-Collection">
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
                                <input type="hidden" name="minitalk_id" value="{{$minitalk->id}}">
                                <button type="submit" class="pull-right btn btn-submit">@lang('labels.reply')</button>
                            </form>
                        </div>
                    </div>
                @endif

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
                this.$http.get('{{url("/minitalkComments/" . $minitalk->id)}}', function (response) {
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
                    minitalk_id: '{{$minitalk->id}}',
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
                    this.$http.post('{{url("/minitalks/" . $minitalk->id . '/favorite')}}', function (response) {
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
                    this.$http.post('{{url("/minitalks/" . $minitalk->id . '/collect')}}', function (response) {
                    }.bind(this));
                },

                onPostComment: function (e) {
                    e.preventDefault();

                    var comment = this.newComment;
                    var post = this.newPost;
                    post.content = ue.getContent();

                    if (removeHTML(post.content).length < 10) {
                        toastr.error("@lang('labels.tooShortComment')");
                        return;
                    }
                    comment.content = post.content;

                    this.$http.post('/minitalkComments', post, function (data) {
                        this.comments.unshift(comment);

                        toastr.success("@lang('labels.feelFreeToComment')", "@lang('labels.commentSuccess')");
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