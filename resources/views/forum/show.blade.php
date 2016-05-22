@extends('app')

@section('title')
    @lang('titles.forum')
@endsection

@section('content')
    <div class="body">
        <div class="Header"></div>
        <div class="Header"></div>

        <div class="Card-Collection">
            <?php $now = \Carbon\Carbon::now(); ?>
            <div class="media discuss">
                <h3 class="discuss-title">
                    {{$discussion->title}}
                    @if($discussion->fixtop_expire_at->gt($now))
                        {{--<i class="glyphicon glyphicon-arrow-up red"></i>--}}
                        <span class="label label-success pull-right">@lang('labels.fixtop') </span>
                    @endif
                </h3>
                <a class="media-left" href="{{url('users/' . $discussion->owner->id)}}">
                    <img src="{{$discussion->owner->avatar}}" alt="64x64"
                         class="img-circle media-object" width="64px" height="64px">
                </a>

                <div class="media-body discuss-content">
                    <h5 class="media-heading">
                        {{$discussion->lastAnswerBy->name}}
                        <span class="time ">{{$discussion->updated_at->diffForHumans()}}</span>
                    </h5>
                    {!! $html !!}
                </div>
            </div>

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
                                        <span class="time">{{$comment->updated_at->diffForHumans()}}</span>
                                    </h5>
                                    <p class="discuss-content">{!! $comment->content !!}</p>
                                </div>

                                @if(!Auth::guest())
                                    <div class="comment-footer">
                                        <button class="btn btn-like" id='comment-{{$comment->id}}'
                                                onclick="like({{$comment->id}})">@lang('labels.like')
                                            <i class="glyphicon glyphicon-thumbs-up"></i>
                                        <span id="comment-like-{{$comment->id}}">
                                            {{$comment->likesNum}}
                                        </span>
                                        </button>
                                        <button class="btn btn-reply"
                                                onclick="reply({{$comment->owner->id . ',"' . $comment->owner->name . '"'}})">@lang('labels.reply')</button>
                                    </div>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>


            @include('UEditor::head')
            <form action="{{url('comments')}}" method="POST" id="replyForm">
                {{csrf_field()}}

                        <!-- 加载编辑器的容器 -->
                <script id="container" style="width: 100%; height : 100px; margin-bottom: 20px" name="content" type="text/plain"></script>
                <input type="hidden" name="discussion_id" value="{{$discussion->id}}">
                <button type="submit" class="pull-right btn btn-submit">@lang('labels.submit')</button>
            </form>
        </div>

        <div class="Header"></div>
        @include('smallBeach')
    </div>
@endsection

@section('otherjs')
    <script>

        var ue = UE.getEditor('container', {
            toolbars: [
                ['fullscreen', 'source', 'undo', 'redo', '|', 'removeformat', 'formatmatch', 'selectall', 'cleardoc',],
                ['bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'fontsize', '|', 'insertimage', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist']
            ]
        });
        ue.ready(function () {
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
        });

        var container = $('#container');
        function reply(userId, userName) {
            window.location.href = "#replyForm";
            var append = true;
            ue.setContent('<a href="/users/' + userId + '">@' + userName + '</a>', append);
        }

        function like(commentId) {
            $.get('{{url('comments/like/')}}' + '/' + commentId, function (response) {
                if (response.status == 200) {
                    comment = $('#comment-like-' + commentId);
                    var likes = comment.text();
                    if (likes == '') {
                        likes = 0;
                    }
                    console.log(response.data.message);
                    if (response.data.message == 'like') {
                        comment.text(parseInt(likes) + 1);
                    } else {
                        comment.text(parseInt(likes) - 1);
                    }
                }
            });
        }
    </script>
@endsection
