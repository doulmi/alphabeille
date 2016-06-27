@extends('app')

@section('title')
    {{ $lesson->title }}
@endsection
@section('content')
    <link rel="stylesheet" href="/css/audioplayer.css"/>
    <div class="body grey">

        <?php $canRead = $lesson->free || (!Auth::guest() && Auth::user()->level() > 1) ?>
        <div class="Header"></div>

        <div class="lesson-content">
            <div class="explanations">
                @if(ends_with(Request::fullUrl(), '/zh_CN'))
                    <a href="{{url('lessons/' . $lesson->id)}}">@lang('labels.sub_fr')</a>
                @else
                    <a href="{{url('lessons/' . $lesson->id)}}/zh_CN">@lang('labels.sub_zh_CN')</a>
                @endif
            </div>

            <br/>
            {{--<div class="Card-Collection">--}}
            <h1 class="mar-t-z center">
                {{ $lesson->title }}
            </h1>

            <div class="center">
                Par <a href="{{url('/')}}">alpha-beille.com</a> | {{$lesson->created_at}}
            </div>
            <br/>

            <a class="btn btn-label label-topic">{{ $topic->title }}</a>
            <a class="btn btn-label label-{{$topic->sex}}">@lang("labels.tags." . $topic->sex)</a>
            @if($canRead)
                <div class="playerPanel">
                    <audio id='audio' preload="auto" controls hidden>
                        <source src="{{$lesson->audio_url}}"/>
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
            {{--<div class="Header"></div>--}}

            {{--<div class="Video-information row">--}}
            {{--<div class="Video-buttons Box">--}}
            {{--<ul class="utility-naked-list ">--}}
            {{--@if($lesson->free || (!Auth::guest() && Auth::user()->level() > 1))--}}
            {{--<li>--}}
            {{--<a href="{{ url('audios/' . $lesson->id) }}" class="Button-with-icon">--}}
            {{--<i class="glyphicon glyphicon-download-alt"></i>--}}
            {{--<span>@lang('labels.download') </span>--}}
            {{--</a>--}}
            {{--</li>--}}
            {{--@endif--}}

            {{--<li>--}}
            {{--<a href="#disqus_thread" class="Button-with-icon">--}}
            {{--<i class="glyphicon glyphicon-comment"></i>--}}
            {{--<span>@lang('labels.discuss') </span>--}}
            {{--</a>--}}
            {{--</li>--}}
            {{--</ul>--}}
            {{--</div>--}}

            {{--<div class="Video-details Box Box-Large">--}}
            {{--<div class="Video-body">--}}
            {{--<p class="Lesson-decription">{{ $lesson->description }}</p>--}}
            {{--<p class="mar-t">--}}
            {{--<strong>--}}
            {{--{{trans('labels.publishOn') . ' ' . $lesson->created_at->diffForHumans()}}--}}
            {{--</strong>--}}
            {{--</p>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}

            {{--<div class="Card-Collection">--}}
            @if($lesson->free || (!Auth::guest() && Auth::user()->level() > 1))
                <div class='markdown-content'>
                    {!! $content !!}
                </div>
            @endif
            {{--</div>--}}

            <div class="Header"></div>

        </div>
        {{--@include('sugguest')--}}
        <div class="Card-Collection ">
            <div class="Header"></div>
            <h2 class="Heading-Fancy row">
            <span class="Heading-Fancy-subtitle black">
                @lang('labels.learnSlagon')
            </span>
                <span class='title black'>{{ $topic->title }}</span>
            </h2>

            {{--<div class="Card-Collection">--}}
            <table class="table lessons-table table-hover">
                <tbody>
                @foreach($topic->lessons()->get() as $i => $les)
                    @if($les->id == $lesson->id)
                        <tr class="lesson-row active-row">
                    @else
                        <tr class="lesson-row">
                            @endif
                            <th scope="row">{{$i + 1}}.</th>
                            <td onclick="window.document.location='{{url('lessons/' . $les->id)}}'">
                                {{$les->title}}
                                @if($les->free)
                                    <span class="free-label">@lang('labels.free')</span>
                                @endif
                            </td>
                            <td>{{$les->duration}}</td>
                        </tr>
                        @endforeach
                </tbody>
            </table>
            <div class="Header"></div>
            <h2 class="Heading-Fancy row">
                <span class='title black'>{{ trans('labels.suggestTopics')}}</span>
            </h2>

            {{--<div class="Card-Collection">--}}
            <div class="row">
                @include('topics.topicsList')
            </div>
            {{--</div>--}}
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
                                <input type="hidden" name="lesson_id" value="{{$lesson->id}}">
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
                                        @{{{  comment.content }}}
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

    @include('smallBeach')
@endsection

@section('otherjs')
    <script src="/js/audio.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.0/vue-resource.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>

    <script>
        $('img.Card-image').lazyload();

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


        //            console.log(audios[0].currentTime);
        //            audios[0].currentTime = 10;


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
                @if(!Auth::guest())
                        this.$http.get('{{url("/lessonComments/" . $lesson->id)}}', function (response) {
                    this.comments = response;
                }.bind(this));
                this.comment_visible = true;
                @endif
            },
            data: {
                comments: [],
                comment_visible: false,
                newComment: {
                    name: '{{Auth::user() ? Auth::user()->name : ''}}',
                    avatar: '{{Auth::user() ? Auth::user()->avatar : ''}}',
                    content: ''
                },

                newPost: {
                    lesson_id: '{{$lesson->id}}',
                    content: ''
                }
            },

            methods: {
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
                    this.$http.post('/lessonComments', post, function (data) {
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