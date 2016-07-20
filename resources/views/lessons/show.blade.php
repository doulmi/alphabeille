@extends('app')

@section('title')
    {{ $lesson->title }}
@endsection

@section('header')
    <meta name="description" content="{{$lesson->description}}">
    <meta name="Keywords" content="{{ $lesson->keywords }}">
@endsection

@section('content')
    <meta property="og:title" content="{{$lesson->title}}" />
    <meta property="og:image" content="{{$lesson->avatar}}" />

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
            <h1 class="mar-t-z center">
                {{ $lesson->title }}
            </h1>

            <div class="center">
                Par <a href="{{url('/')}}">alpha-beille.com</a> | {{$lesson->created_at}}
            </div>
            <br/>
            <br/>

            <a href="{{url("topics/" . $topic->id )}}" class="btn btn-label label-topic">{{ $topic->title }}</a>
            <a class="btn btn-label label-{{$topic->sex}}">@lang("labels.tags." . $topic->sex)</a>

            @if($canRead)
                <div class="playerPanel">
                    <audio id='audio' preload="auto" controls hidden>
                        <source src="{{$lesson->audio_url}}"/>
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
            @else
                @include('blockContent')
            @endif

            <br/>
            <br/>
            @if($lesson->free || (!Auth::guest() && Auth::user()->level() > 1))
                <div class='markdown-content'>
                    {!! $content !!}
                </div>
            @endif

            @if(!Auth::guest())
                <div class="center">
                    <a href="#" data-tooltips="@lang('labels.favorite')" @click.stop.prevent="favoriteEvent">
                        <div class="heart" v-bind:class="favorite"></div>
                    </a>

                    @if(!$punchin)
                        <a id="punchinlink" href="#" data-tooltips="@lang('labels.punchin')" @click.stop.prevent="punchinEvent">
                            <i class="favorite glyphicon glyphicon-ok"></i>
                        </a>
                    @endif

                    <a href="#" data-tooltips="@lang('labels.collect')" @click.stop.prevent="collectEvent">
                       <div class="collect" v-bind:class="collect"></div>
                    </a>

                </div>
                <div class="share-component share-panel" data-sites="wechat, weibo ,facebook"
                     data-description="@lang('labels.shareTo')" data-image="{{$lesson->avatar}}">
                    @lang('labels.share'):
                </div>
            @endif
        </div>
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
            @include('topics.topicsList')
            {{--</div>--}}

            <div id="disqus_thread">
                <h1 class="black">@lang('labels.comments')</h1>
                @if(Auth::guest())
                    <div class="">
                        <div class="center">
                            <a href="{{url('login')}}">@lang('labels.login')</a>
                            @lang('labels.loginToReply')
                        </div>
                    </div>
                @else
                    <div class="media ">
                        <div class="media-left">
                            <a href="#">
                                <img src="{{Auth::user()->avatar}}" alt="avatar" class="img-circle media-object avatar" >
                            </a>
                        </div>

                        <div class="media-body">
                            <form v-on:submit="onPostComment" method="POST"
                                  id="replyForm">
                                {{csrf_field()}}

                                <textarea name="content" data-provide="markdown" rows="10" v-model="newPost.content"  placeholder="@lang('labels.addComment')" id="comment-content"></textarea>
                                <input type="hidden" name="lesson_id" value="{{$lesson->id}}">
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
                                    <img src="@{{comment.avatar}}" alt="avatar" class="img-circle media-object avatar">
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
        </div>
        <div id='goTop'></div>
    </div>

{{--    @include('smallBeach')--}}
@endsection

@section('otherjs')
    <script src="/js/audio.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.0/vue-resource.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>
    <script src="/js/social-share.min.js"></script>

    <script>
        $('img.Card-image').lazyload();
        var isIE = function (ver) {
            var b = document.createElement('b');
            b.innerHTML = '<!--[if IE ' + ver + ']><i></i><![endif]-->';
            return b.getElementsByTagName('i').length === 1
        };

        $('#goTop').goTop();
        function reply(userId, userName) {
            window.location.href = "#replyForm";
            ue.setContent('<a href="/users/' + userId + '">@' + userName + '</a>', false);
        }

        function removeHTML(strText) {
            var regEx = /<[^>]*>/g;
            return strText.replace(regEx, "");
        }
        
        $(function() {
                $(".heart").on("click", function () {
                    $(this).toggleClass("is-active");
                });
                $(".collect").on("click", function () {
                    $(this).toggleClass("is-active");
                });
        });

        Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
        new Vue({
            el: 'body',

            ready: function () {
                this.$http.get('{{url("lessonComments/" . $lesson->id)}}', function (response) {
                    console.log(response);
                    this.comments = response;
                    this.comment_visible = true;
                }.bind(this));

            },
            data: {
                comments: [],
                comment_visible: false,
                favorite: '{{$like ? 'is-active' : ''}}',
                isFavorite: '{{$like}}',
                collect: '{{$collect ? 'is-active' : ''}}',
                isCollect: '{{$collect}}',
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
                favoriteEvent() {
                    this.$http.post('{{url("/lessons/" . $lesson->id . '/favorite')}}', function (response) {
                    }.bind(this));
                },

                collectEvent() {
                    this.$http.post('{{url("/lessons/" . $lesson->id . '/collect')}}', function (response) {
                    }.bind(this));
                },

                punchinEvent() {
                    var punchin = $('#punchin');
                    $('#punchinlink').hide();

                    this.$http.post('{{url("/lessons/" . $lesson->id . '/punchin')}}', function (response) {
                        punchin.html(parseInt(punchin.html()) + 1);
                        var series = response.series;
                        var isBreakup = response.break;
                        if (isBreakup) {
                            @if(!Auth::guest())
                            toastr.success("@lang('labels.punchinSuccess')" + "@lang('labels.breakup')" + "@lang('labels.continuePunchin', ['day' => Auth::user()->series + 1])");
                            @endif
                        }
                    }.bind(this));
                },

                onPostComment: function (e) {
                    e.preventDefault();

                    var comment = this.newComment;
                    var post = this.newPost;

                    if (removeHTML(post.content).length < 10) {
                        toastr.error("@lang('labels.tooShortComment')");
                        return;
                    }
                    comment.content = post.content;

                    this.$http.post('/lessonComments', post, function (data) {
                        this.comments.unshift(comment);
                        this.newPost.content= '';

                        toastr.success("@lang('labels.feelFreeToComment')", "@lang('labels.commentSuccess')");
                        comment = {
                            name: '{{Auth::user() ? Auth::user()->name : ''}}',
                            avatar: '{{Auth::user() ? Auth::user()->avatar: ''}}',
                            body: ''
                        };
                    }.bind(this))
                }
            }
        });
    </script>
@endsection