@section('title'){{ $readable->title }}@endsection

@section('header')
    <meta name="description" content="{{$readable->description}}">
    <meta name="Keywords" content="{{ $readable->keywords }}">
@endsection

@section('content')
    <meta property="og:title" content="{{$readable->title}}"/>
    <meta property="og:image" content="{{$readable->avatar}}"/>
    <meta property="og:type" content="audio" />
    <meta property="og:url" content="{{Request::getUri()}}" />
    <meta property="og:description" content="{{$readable->description}}"/>

    <div>
        <?php $canRead = $readable->free || (Auth::user() && (Auth::user()->can('videos.subs'))) ?>

        <div class="Header"></div>

        <div class="lesson-content">
            <div class="explanations">
                @if($readable instanceof \App\Lesson && $canRead)
                    @if($readable instanceof \App\Lesson && ends_with(Request::fullUrl(), '/zh_CN'))
                        <a href="{{url($type . 's/' . $readable->id)}}">@lang('labels.sub_fr')</a>
                    @else
                        <a href="{{url($type . 's/' . $readable->id)}}/zh_CN">@lang('labels.sub_zh_CN')</a>
                    @endif
                @endif
            </div>

            <br/>
            <h1 class="center">
                {{ $readable ->title }}
            </h1>

            <div class="author">
                Par <a href="{{url('/')}}">alpha-beille.com</a> | {{$readable->created_at}}
            </div>

            @if($readable instanceof \App\Lesson)
                <a href="{{url("topics/" . $topic->id )}}" class="btn btn-label label-topic">{{ $topic->title }}</a>
                <a class="btn btn-label label-{{$readable->sex}}">@lang("labels.tags." . $readable->sex)</a>
            @endif

            {{--@if(!$readable instanceof \App\Video)--}}
            <div class="playerPanel">
                <audio id='audio' preload="auto" controls hidden>
                    <source src="{{$readable->audio_url}}"/>
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
            @if(!$canRead)
                <center>
                    <a href="{{url('menus')}}" class="btn btn-ads">@lang('labels.adBtnShort')</a><br/>
                </center>
            @endif


            @if($canRead)
                <div class='markdown-content'>
                    {!! $content !!}
                </div>
            @endif

            @if($readable instanceof \App\Minitalk)
                <div class='markdown-content wechat-part'>
                    {!! $wechat_part !!}
                </div>
            @endif


            @if(!Auth::guest())
                <div class="center">
                    <a href="#" data-tooltips="@lang('labels.favorite')" @click.stop.prevent="favoriteEvent">
                        <div class="heart" v-bind:class="favorite"></div>
                    </a>

                    @if(!$punchin)
                        <a id="punchinlink" class="hidden-xs fancy-button" href="#"
                           data-tooltips="@lang('labels.punchin')" @click.stop.prevent="punchinEvent">
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
                           data-tooltips="@lang('labels.punchin')" @click.stop.prevent="punchinEvent">
                            <div class="left-frills frills"></div>
                            <div class="button-frilles">@lang('labels.punchin')</div>
                            <div class="right-frills frills"></div>
                        </a>
                    @endif
                </div>
                <div class="share-component share-panel" data-sites="wechat, weibo, qzone, qq, douban"
                     data-description="@lang('labels.shareTo')" data-image="{{$readable->avatar}}" data-weibo-title="我正在Alphabeille看短视频学法语，已经坚持{{Auth::user()->series}}天，学习了{{Auth::user()->learnedVideos()->count()}}个视频，{{Auth::user()->learnedMinitalks()->count()}}个脱口秀，总计{{Auth::user()->mins()}}">
                    @lang('labels.share'):
                </div>
            @endif
        </div>

        <div class="Card-Collection">
            {{--同一主题内容--}}
            @if($readable instanceof \App\Lesson)
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
                        @if($les->id == $readable->id)
                            <tr class="lesson-row active-row">
                        @else
                            <tr class="lesson-row">
                                @endif
                                <th scope="row">{{$i + 1}}.</th>
                                <td onclick="window.document.location='{{url('lessons/' . $les->slug)}}'">
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
            @endif

            {{--推荐部分--}}
            <div class="Header hidden-xs"></div>
            @if($readable instanceof \App\Lesson)
                <h2 class="Heading-Fancy row">
                    <span class='title black'>{{ trans('labels.suggestLessons')}}</span>
                </h2>
                <?php $readables = $lessons; $type = 'lesson'?>
                @include('components.readableList')
            @elseif($readable instanceof \App\Talkshow)
                <h2 class="Heading-Fancy row">
                    <span class='title black'>{{ trans('labels.suggestTalkshows')}}</span>
                </h2>
                <?php $readables = $talkshows; $type = 'talkshow'?>
                @include('components.readableList')
            @endif

            <div id="disqus_thread">
                <h1 class="black">@lang('labels.comments')</h1>
                @if(Auth::guest())
                    <div class="center">
                        <a href="{{url('login')}}">@lang('labels.login')</a>
                        @lang('labels.loginToReply')
                    </div>
                @else
                    <div class="media">
                        <div class="media-left">
                            <a href="#">
                                <img src="{{Auth::user()->avatar}}" alt="avatar"
                                     class="img-circle media-object avatar hidden-xs">
                            </a>
                        </div>

                        <div class="media-body">
                            <form v-on:submit="onPostComment" method="POST"
                                  id="replyForm">
                                {{csrf_field()}}

                                <textarea name="content" data-provide="markdown" v-model="newPost.content"
                                          placeholder="@lang('labels.addComment')" id="comment-content"></textarea>
                                <input type="hidden" name="id" value="{{$readable->id}}">
                                <button type="submit" class="pull-right btn btn-submit">@lang('labels.reply')</button>
                            </form>
                        </div>
                    </div>
                @endif

                <div class="comments" v-if="commentVisible">
                    <ul id="comments">
                        <li v-for="comment in comments" class="comment">
                            <div class="media">
                                <a class="media-left">
                                    <img v-bind:src="comment.avatar" alt="avatar"
                                         class="img-circle media-object avatar">
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
        </div>
        <div class="Header hidden-xs"></div>
        <div id='goTop'></div>
    </div>

    {{--    @include('smallBeach')--}}
@endsection

@section('otherjs')
    <script src="/js/audio.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.0/vue-resource.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>

    <script>
        $('img.Card-image').lazyload();

        $('#goTop').goTop();
        function reply(userId, userName) {
            window.location.href = "#replyForm";
            ue.setContent('<a href="/users/' + userId + '">@' + userName + '</a>', false);
        }

        function removeHTML(strText) {
            var regEx = /<[^>]*>/g;
            return strText.replace(regEx, "");
        }

        $(function () {
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

            $(".markdown-content span").click(activePopover);
        });

        Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
        new Vue({
            el: 'body',

            ready: function () {
                this.$http.get('{{url($type . "Comments/" . $readable->id)}}', function (response) {
                    this.comments = response;
                }.bind(this));

            },
            data: {
                comments: [],
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
                    id: '{{$readable->id}}',
                    content: ''
                }
            },

            computed: {
                commentVisible() {
                    return this.comments instanceof Array && this.comments.length > 0;
                }
            },
            methods: {
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

                onPostComment: function (e) {
                    e.preventDefault();

                    var comment = this.newComment;
                    var post = this.newPost;

                    if (removeHTML(post.content).length < 10) {
                        toastr.error("@lang('labels.tooShortComment')");
                        return;
                    }
                    comment.content = post.content;

                    this.$http.post("{{url('/'. $type .'Comments')}}", post, function (data) {
                        this.comments.unshift(comment);
                        this.newPost.content = '';

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

