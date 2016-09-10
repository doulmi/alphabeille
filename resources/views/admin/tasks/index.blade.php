@extends('admin.index')

@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            {{Session::get('success')}}
        </div>
    @endif
    <form action="{{url('admin/tasks')}}" id="taskForm">
        <div class="">
            <div class="btn-group">
                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                   aria-haspopup="true"
                   aria-expanded="false">
                    @if(Request::has('type'))
                        @lang('labels.videos.state' . Request::get('type', ''))
                    @else
                        @lang('labels.tasks.all')
                    @endif
                    <span class="caret"></span>
                </a>

                {{--类型--}}
                <ul class="dropdown-menu">
                    <li><a onclick="filterType('')">@lang('labels.tasks.all')</a></li>
                    @foreach($types as $type)
                        <li>
                            <a onclick="filterType('{{$type}}')">@lang('labels.videos.state' . $type)</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="btn-group">
                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                   aria-haspopup="true"
                   aria-expanded="false">
                    @lang('labels.tasks.state' . Request::get('state', ''))
                    <span class="caret"></span>
                </a>

                {{--完成度--}}
                <ul class="dropdown-menu">
                    <li><a onclick="filterState('')">所有状态</a></li>
                    <li><a onclick="filterState(0)">未完成</a></li>
                    <li><a onclick="filterState(1)">待确认</a></li>
                    <li><a onclick="filterState(2)">已确认</a></li>
                </ul>
            </div>

            <div class="btn-group">
                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                   aria-haspopup="true"
                   aria-expanded="false">
                    @if(Request::get('doubt', ''))
                        @lang('labels.trouble')
                    @else
                        @lang('labels.no')
                    @endif
                    <span class="caret"></span>
                </a>

                {{--完成度--}}
                <ul class="dropdown-menu">
                   <li><a onclick="filterDoubt(0)">@lang('labels.no')</a></li>
                   <li><a onclick="filterDoubt(1)">@lang('labels.trouble')</a></li>
                </ul>
            </div>

            <div class="btn-group">
                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                   aria-haspopup="true"
                   aria-expanded="false">
                    {{Request::get('userName') == '' ? 'ALL' : Request::get('userName') }}
                    <span class="caret"></span>
                </a>

                {{--完成度--}}
                <ul class="dropdown-menu">
                    <li>
                        <a onclick="filterUser('')">ALL</a>
                    </li>
                    @foreach($translators as $translator)
                        <li>
                            <a onclick="filterUser('{{$translator->id}}', '{{$translator->name}}')">{{$translator->name}}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        {!! csrf_field() !!}
        <div class="fullscreen">
            <table class="table">
                <thead>
                <tr>
                    <th>id</th>
                    <th>@lang('labels.state')</th>
                    <th>@lang('labels.avatar')</th>
                    <th>@lang('labels.title')</th>
                    <th>@lang('labels.translator')</th>
                    <th>@lang('labels.updated_at')</th>
                    <th>@lang('labels.actions')</th>
                </tr>
                </thead>
                <tbody id="tbody">
                @foreach($videos as $i => $video)
                    <tr id="row-{{$video->video_id}}">
                        {{--<td scope="row">{{$video->video_id}}</td>--}}
                        <td scope="row">{{$i+1}}</td>
                        <td>@lang('labels.videos.state' . $video->state)</td>
                        <td scope="row"><img src="{{$video->avatar}}" alt="" width="50px" height="50px"></td>
                        <td><a href="{{url('videos/' . $video->video_id)}}" TARGET="_blank">{{$video->title}}</a></td>
                        <td>{{$video->name}}</td>
                        <td>{{$video->duration}}</td>
                        <td>{{$video->updated_at}}</td>
                        <td>
                            <a class="btn btn-info"
                               href="{{url('admin/tasks/' . $video->id .'/translate')}}">@lang('labels.modify')</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="center">
                {!! $videos->links() !!}
            </div>
            @if(Request::has('orderBy') )
                <input type="hidden" name="orderBy" value="{{ Request::get('orderBy')}}">
            @endif
            @if(Request::has('dir'))
                <input type="hidden" name="dir" value="{{ Request::get('dir')}}">
            @endif
            @if(Request::has('limit'))
                <input type="hidden" name="limit" value="{{ Request::get('limit')}}">
            @endif
            <input type="hidden" id="state" name="state" value="{{ Request::get('state', '') }}">
            <input type="hidden" id="type" name="type" value="{{Request::get('type', '')}}">
            <input type="hidden" id="user" name="user" value="{{Request::get('user', '')}}">
            <input type="hidden" id="userName" name="userName" value="{{Request::get('userName', '')}}">
            <input type="hidden" id="doubt" name="doubt" value="{{Request::get('doubt', '')}}">
        </div>
    </form>
@endsection

@section('otherjs')
    <script src="/js/adminFullscreen.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.0/vue-resource.min.js'></script>

    <script>
        function deleteContent(id) {
            $.ajax({
                type: "DELETE",
                url: '{{url('admin/videos/')}}' + '/' + id,
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                success: function (response) {
                    $('#row-' + id).remove();
                }
            });
        }

        function clearSearch() {
            $('#search').val('');
            $('#videoForm').submit();
        }

        var changeLevel = function (videoId, level) {
            $('#videoLevel-' + videoId).text(level);
            $.get('{{ url('/admin/videos/changeLevel/') }}' + '/' + videoId + '/' + level, function (response) {
                console.log(response.data['message']);
            });
        };

        var changeState = function (videoId, level, text) {
            $('#videoState-' + videoId).text(text);
            $.get('{{ url('/admin/videos/changeState/') }}' + '/' + videoId + '/' + level, function (response) {
            });
        };

        var stateInput = $('#state');
        var userInput = $('#user');
        var userNameInput = $('#userName');
        var typeInput = $('#type');
        var form = $('#taskForm');
        var doubt = $('#doubt');

        var filterState = function (state) {
            stateInput.val(state);
            form.submit();
        };

        var filterType = function (type) {
            typeInput.val(type);
            form.submit();
        };

        var filterUser = function (user, name) {
            userInput.val(user);
            userNameInput.val(name);
            form.submit();
        };

        var filterDoubt = function($toggle) {
            doubt.val($toggle);
            form.submit();
        }
    </script>

@endsection
