@extends('app')

@section('title')
    {{ $lesson->title }}
@endsection
@section('content')
    <div class="body">
        <div class="Header"></div>
        <div class="Header"></div>
        <div class="Card-Collection">
            <h2 class="mar-t-z center">
                {{ $lesson->title }}
            </h2>
            @if($lesson->free || (!Auth::guest() && Auth::user()->level() > 1))
                <audio id='audio' preload="auto" controls hidden>
                    <source src="https://raw.githubusercontent.com/kolber/audiojs/master/mp3/bensound-dubstep.mp3"/>
                </audio>
            @else
                @include('blockContent')
            @endif

            <div class="Video-information row">
                <div class="Video-buttons Box">
                    <ul class="utility-naked-list ">
                        @if($lesson->free || (!Auth::guest() && Auth::user()->level() > 1))
                            <li>
                                <a href="{{ url('audios/' . $lesson->id) }}" class="Button-with-icon">
                                    <i class="glyphicon glyphicon-download-alt"></i>
                                    <span>@lang('labels.download') </span>
                                </a>
                            </li>
                        @endif

                        <li>
                            <a href="#disqus_thread" class="Button-with-icon">
                                <i class="glyphicon glyphicon-comment"></i>
                                <span>@lang('labels.discuss') </span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="Video-details Box Box-Large">
                    <div class="Video-body">
                        <p class="Lesson-decription">{{ $lesson->description }}</p>
                        <p class="mar-t">
                            <strong>
                                {{trans('labels.publishOn') . ' ' . $lesson->created_at->diffForHumans()}}
                            </strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="Header"></div>
        <h2 class="Heading-Fancy row">
            <span class="Heading-Fancy-subtitle">
                @lang('labels.learnSlagon')
            </span>
            <span class='title'>{{ $topic->title }}</span>
        </h2>

        <div class="Card-Collection">
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
                            <td>14:32</td>
                        </tr>
                        @endforeach
                </tbody>
            </table>
        </div>

        {{--        @include('sugguest')--}}
        <div class="Header"></div>
        <h2 class="Heading-Fancy row">
            <span class='title'>{{ trans('labels.suggestTopics')}}</span>
        </h2>

        <div class="Card-Collection">
            <div class="row">
                @include('topics.topicsList')
            </div>
        </div>
        <div class="Header"></div>

        <div class="Card-Collection" id="disqus_thread">
            <h1 class="white">@lang('labels.comments')</h1>
            @if(Auth::guest())
                <div class="reply-panel">
                    <div class="Header"></div>
                    <div class="center">
                        <a href="{{url('login')}}">@lang('labels.login')</a>
                        @lang('labels.loginToReply')
                    </div>
                    <div class="Header"></div>
                </div>
            @else
                <div class="media reply-panel">
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
                        <form action="{{url('lessonComments')}}" method="POST" id="replyForm">
                            {{csrf_field()}}

                            @include('UEditor::head')
                            <!-- 加载编辑器的容器 -->
                            <script id="container" style="width: 100%; height : 100px" name="content"
                                    type="text/plain" placeholder="@lang('labels.addComment')"></script>
                            <input type="hidden" name="lesson_id" value="{{$lesson->id}}">
                            <button type="submit" class="pull-right btn btn-submit">@lang('labels.reply')</button>
                        </form>
                    </div>
                </div>
            @endif

            <div class="comments">
                <ul id="comments">
                    @foreach($comments as $comment)
                        <li class="comment">
                            <div class="media">
                                <a class="media-left" href="{{url('users/' . $comment->owner->id)}}">
                                    <img src="{{$comment->owner->avatar}}" alt="64x64"
                                         class="img-circle media-object" width="64px" height="64px">
                                </a>

                                <div class="media-body">
                                    <h5 class="media-heading">
                                        {{$comment->owner->name}}
                                    </h5>
                                    <p class="discuss-content">{!! $comment->content !!}</p>
                                    <span class="time">{{$comment->updated_at->diffForHumans()}}</span>
                                </div>

                                @if(!Auth::guest())
                                    <div class="comment-footer">
                                        <button class="btn btn-reply"
                                                onclick="reply({{$comment->owner->id . ',"' . $comment->owner->name . '"'}})">@lang('labels.reply')</button>
                                    </div>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="Header"></div>
        <div class="Header"></div>
    </div>

    @include('smallBeach')
@endsection

@section('otherjs')
    <script src="/js/audio.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>

    <script>
        $('img.Card-image').lazyload();
        var ue = UE.getEditor('container', {
            toolbars: [
                ['fullscreen', 'source', 'undo', 'redo', '|', 'removeformat', 'formatmatch', 'selectall', 'cleardoc',],
                ['bold', 'italic', 'underline', 'fontborder', 'strikethrough','|', 'insertimage', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist']
            ],
            focus: true,
            elementPathEnabled: false,
            maximumWords: 1000
        });
        ue.ready(function () {
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
        });

        function reply(userId, userName) {
            window.location.href = "#replyForm";
            ue.setContent('<a href="/users/' + userId + '">@' + userName + '</a>', false);
        }

        var isIE = function (ver) {
            var b = document.createElement('b')
            b.innerHTML = '<!--[if IE ' + ver + ']><i></i><![endif]-->'
            return b.getElementsByTagName('i').length === 1
        };

        if (isIE(6) || isIE(7) || isIE(8)) {

        }

        $(function () {
            var audios = $('#audio');
            audios.audioPlayer();

//            var audio = document.querySelector('#audio');
//
//            $(document).keydown(function (e) {
//                var unicode = e.charCode ? e.charCode : e.keyCode;
//                switch (unicode) {
//                    case 13:
//                        break;
//                    case 39:
//                        break;
//                    case 37:
//                        break;
//                }
//            });
//
//            var avance = function (time) {
//                audio.element.currentTime += time;
//            };
//
//            var back = function (time) {
//                audio.element.currentTime -= time;
//            };
        });

        new Vue({
            el : '#comments',
            data: {
            }
        });


    </script>
@endsection