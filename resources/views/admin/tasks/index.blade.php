@extends('admin.index')

@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            {{Session::get('success')}}
        </div>
    @endif
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
                <?php
                $typeParam =  Request::has('type') ? 'type=' . Request::get('type') : '';
                $userParam = Request::has('user') ? 'user=' .Request::get('user') : '';
                $stateParam = Request::get('state') ? 'state=' . Request::get('state') : '';
                ?>

                <li><a href="{{url('admin/tasks?' . $stateParam)}}">@lang('labels.tasks.all')</a></li>
                @foreach($types as $type)
                    <li>
                        <a href="{{url('admin/tasks?type=' . $type . '&' . $stateParam)}}">@lang('labels.videos.state' . $type)</a>
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
                <li><a href="{{url('admin/tasks?' . $typeParam . '&' . $userParam)}}">所有状态</a></li>
                <li><a href="{{url('admin/tasks?state=0&' . $typeParam . '&' . $userParam)}}">未完成</a></li>
                <li><a href="{{url('admin/tasks?state=1&' . $typeParam . '&' . $userParam)}}">待确认</a></li>
                <li><a href="{{url('admin/tasks?state=2&' . $typeParam . '&' . $userParam)}}">已确认</a></li>
            </ul>
        </div>

        <div class="btn-group">
            <a class="btn btn-default dropdown-toggle" data-toggle="dropdown"
               aria-haspopup="true"
               aria-expanded="false">
                {{Request::get('name', 'ALL')}}
                <span class="caret"></span>
            </a>

            {{--完成度--}}
            <ul class="dropdown-menu">
                @foreach($translators as $translator)
                    <li> <a href="{{url('admin/tasks?user=' . $translator->id . '&' . $typeParam . '&' . $stateParam)}}">{{$translator->name}}</a> </li>
                @endforeach
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
                @foreach($videos as $i => $video)
                    <tr id="row-{{$video->video_id}}">
                        {{--<td scope="row">{{$video->video_id}}</td>--}}
                        <td scope="row">{{$i+1}}</td>
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

        var changeState = function (videoId, level, text) {
            $('#videoState-' + videoId).text(text);
            $.get('{{ url('/admin/videos/changeState/') }}' + '/' + videoId + '/' + level, function (response) {
            });
        };
    </script>

@endsection
