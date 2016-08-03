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

                    <textarea name="content" data-provide="markdown" rows="10" v-model="newPost.content"
                              placeholder="@lang('labels.addComment')" id="comment-content"></textarea>
                    <input type="hidden" name="id" value="{{$readable->id}}">
                    <button type="submit" class="pull-right btn btn-submit" >@lang('labels.reply')</button>
                </form>
            </div>
        </div>
    @endif

    <div class="comments">
        <ul id="comments">
            <li v-for="comment in comments" class="comment">
                <div class="media">
                    <a class="media-left">
                        <img v-bind:src="comment.avatar" alt="avatar"
                             class="img-circle media-object avatar">
                    </a>

                    <div class="media-body">
                        <h5 class="media-heading">
                            <a href="@{{ comemnt.id }}" class="comment-name">@{{comment.name}}</a> <span
                                    class="time">@{{comment.created_at}}</span>
                        </h5>
                        <p class="discuss-content">
                            @{{{ comment.content }}}
                        </p>

                        @if(!Auth::guest())
                            <div><a class="btn-reply" href="#"
                                    @click.stop.prevent="reply(comment.userId,comment.name)">@lang('labels.reply')</a>
                            </div>
                        @endif
                    </div>

                </div>
            </li>
        </ul>
    </div>
    <div class="Header hidden-xs"></div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.0/vue-resource.min.js"></script>
<script>
    function removeHTML(strText) {
        var regEx = /<[^>]*>/g;
        return strText.replace(regEx, "");
    }

    Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');

    var commentVu = new Vue({
        el: '#disqus_thread',
        data: {
            comments: [],
            submit : false,
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

        ready() {
            this.$http.get('{{url($type . "Comments/" . $readable->id)}}', function (response) {
                this.comments = response;
            }.bind(this));
        },

        methods: {
            reply(userId, userName) {
                window.location.href = "#disqus_thread";
                this.newPost.content = '@' + userName;
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
    })
</script>
