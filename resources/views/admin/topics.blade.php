@extends('admin.index')

@section('content')
    <button type="button" class="btn btn-info" data-toggle="modal"
            data-target="#myModal">@lang('labels.addTopic')</button>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="addTitle">@lang('labels.addTopic')</h4>
                </div>

                <form role="form" action="{{url('admin/topics')}}" method="POST">
                    <input name="_method" type="hidden" value="PUT">
                    {!! csrf_field() !!}

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">@lang('labels.title')</label>
                            <input name='title' type="text" class="form-control" id="title">
                        </div>
                        <div class="form-group">
                            <label for="description">@lang('labels.description')</label>
                            <textarea class="name-input form-control" rows="10" id="description"
                                      name="description"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="avatar">@lang('labels.avatar')</label>
                            <input type="text" class="form-control" id="avatar" name="avatar"/>
                        </div>

                        <div class="form-group">
                            <label for="level">@lang('labels.topicLevel')</label>
                            <div class="btn-group">
                                <button type="button" id='level' class="btn btn-default dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @lang('labels.beginner')<span class="caret"></span>
                                </button>

                                <ul class="dropdown-menu">
                                    <li><a href="#"
                                           onclick="changeLevel('beginner', '@lang('labels.beginner')')">@lang('labels.beginner')</a>
                                    </li>
                                    <li><a href="#"
                                           onclick="changeLevel('intermediate', '@lang('labels.intermediate')')">@lang('labels.intermediate')</a>
                                    </li>
                                    <li><a href="#"
                                           onclick="changeLevel('advanced', '@lang('labels.advanced')')">@lang('labels.advanced')</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <input type="hidden" id='topicLevel' name='topicLevel' value="beginner">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-default">@lang('labels.submit')</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            @lang('labels.close')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="mofidyModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="addTitle">@lang('labels.addTopic')</h4>
                </div>
                    <input name="_method" type="hidden" value="PUT">
                    {!! csrf_field() !!}

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">@lang('labels.title')</label>
                            <input name='title' type="text" class="form-control" id="title" v-model="title">
                        </div>
                        <div class="form-group">
                            <label for="description">@lang('labels.description')</label>
                            <textarea class="name-input form-control" rows="10" id="description"
                                      name="description" v-model="description"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="avatar">@lang('labels.avatar')</label>
                            <input type="text" class="form-control" id="avatar" name="avatar" v-model="avatar"/>
                        </div>

                        <div class="form-group">
                            <label for="level">@lang('labels.topicLevel')</label>
                            <div class="btn-group">
                                <button type="button" id='level' class="btn btn-default dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                                    @{{levelShow}}<span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="#"
                                           @click="changeLevel('beginner', '@lang('labels.beginner')')">@lang('labels.beginner')</a>
                                    </li>
                                    <li><a href="#"
                                           @click="changeLevel('intermediate', '@lang('labels.intermediate')')">@lang('labels.intermediate')</a>
                                    </li>
                                    <li><a href="#"
                                           @click="changeLevel('advanced', '@lang('labels.advanced')')">@lang('labels.advanced')</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <input type="hidden" id='topicLevel' name='topicLevel' value="beginner" v-model="level">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" @click="update" class="btn btn-default">@lang('labels.submit')</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            @lang('labels.close')
                        </button>
                    </div>
            </div>
        </div>
    </div>

    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            {{ Session::get('success')}}
        </div>
    @endif
    <div class="fullscreen">
        <table class="table">
            <thead>
            <tr>
                <th>@lang('labels.avatar')</th>
                <th>@lang('labels.title')</th>
                <th>@lang('labels.description')</th>
                <th>@lang('labels.topicLevel')</th>
                <th>Id</th>
                <th>@lang('labels.actions')</th>
            </tr>
            </thead>
            <tbody id="tbody">
            @foreach($topics as $topic)
                <tr id="row-{{$topic->id}}">
                    <td><img width="60px" height="60px" src="{{$topic->avatar}}" alt=""></td>
                    <td>{{$topic->title}}</td>
                    <td> {{$topic->description}} </td>
                    <td> {{$topic->level}} </td>
                    <th scope="row">{{$topic->id}}</th>
                    <td>
                        <button class="btn btn-danger"
                                onclick="deleteTopic('{{$topic->id}}')">@lang('labels.delete')</button>

                        <button class="btn btn-info"  @click='showModifyModal({{$topic->id}})' data-toggle="modal" data-target="#mofidyModal">@lang('labels.modify')</button>

                        <a class="btn btn-info" href="{{url('admin/lessons/' . $topic->id . '/create')}}">@lang('labels.addLesson')</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('otherjs')
    <script src="/js/adminFullscreen.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.0/vue-resource.min.js'></script>


    <script>
        function deleteTopic(id) {
            $.ajax({
                type: "DELETE",
                url: '{{url('admin/topics/')}}' + '/' + id,
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                success: function (response) {
                    $('#row-' + id).remove();
                }
            });
        }
        var topicLevel = $('#topicLevel');
        var levelShow = $('#level');
        function changeLevel(level, name) {
            topicLevel.val(level);
            levelShow.text(name);
        }

        Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
        new Vue({
            el : 'body',
            data : {
                id : 3,
                title : 'Hello',
                description : 'Hello World',
                avatar : '',
                level : 'beginner',
                levelShow : ''
            },

            methods : {
                showModifyModal(id) {
                    this.id = id;
                    this.$http.get('{{url("api/topics/")}}' + '/' + id, function(response) {
                        this.title = response.data.title;
                        this.description = response.data.description;
                        this.avatar = response.data.avatar;
                        this.level = response.data.level;
                        this.levelShow = this.level;
                    }.bind(this));
                },

                update() {
                    var data = {
                        title : this.title,
                        description : this.description,
                        avatar : this.avatar,
                        level : this.level,
                        levelShow : this.levelShow
                    };

                    this.$http.put('{{url("admin/topics/")}}' + '/' + this.id, data, function(response) {
                        console.log(response.status);
                        if(response.status == 200) {
                            window.location.href = "topics";
                        }
                    }.bind(this));
                },

                changeLevel(level, levelShow) {
                    this.level = level;
                    this.levelShow = levelShow;
                }
            }
        })
    </script>

@endsection
