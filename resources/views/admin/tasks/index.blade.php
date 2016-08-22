@extends('admin.index')

@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            {{Session::get('success')}}
        </div>
    @endif
    <div class="btn-group">
        <div class="btn-group">
            <a class="btn btn-default dropdown-toggle" data-toggle="dropdown"
               aria-haspopup="true"
               aria-expanded="false">
                @lang('labels.videos.state' . Request::get('state', ''))
                <span class="caret"></span>
            </a>

            {{--类型--}}
            <ul class="dropdown-menu">
                @foreach($types as $type)
                <li><a href="{{url('admin/tasks')}}">所有</a> </li>
                @endforeach
            </ul>

            {{--完成度--}}
            <ul class="dropdown-menu">
                <li><a href="{{url('admin/tasks')}}">所有状态</a> </li>
                <li><a href="{{url('admin/tasks')}}">未完成</a> </li>
                <li><a href="{{url('admin/tasks')}}">已确认</a> </li>
                <li><a href="{{url('admin/tasks')}}">待确认</a> </li>
            </ul>
        </div>
    </div>
    <form id="videoForm" action="{{url('admin/videos')}}" method="GET">
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
                    <th>@lang('labels.created_at')</th>
                    <th>@lang('labels.actions')</th>
                </tr>
                </thead>
                <tbody id="tbody">
                @foreach($videos as $video)
                    <tr id="row-{{$video->video_id}}">
                        <td scope="row">{{$video->video_id}}</td>
                        <td>@lang('labels.videos.state' . $video->state)</td>
                        <td scope="row"><img src="{{$video->avatar}}" alt="" width="50px" height="50px"></td>
                        <td><a href="{{url('videos/' . $video->video_id)}}" TARGET="_blank">{{$video->title}}</a></td>
                        <td>{{$video->name}}</td>
                        <td>{{$video->created_at}}</td>
                        <td>
                            <a class="btn btn-info"
                               href="{{url('admin/tasks/' . $video->id .'/translate')}}">@lang('labels.modify')</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="center">
                {!! $videos->render() !!}
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

        var changeState = function(videoId, level, text) {
            $('#videoState-' + videoId).text(text);
            $.get('{{ url('/admin/videos/changeState/') }}' + '/' + videoId + '/' + level, function (response) {
            });
        };
    </script>

@endsection
