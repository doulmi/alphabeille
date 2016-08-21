@extends('admin.index')

@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            {{Session::get('success')}}
        </div>
    @endif
    <form id="videoForm" action="{{url('admin/videos')}}" method="GET">
        {!! csrf_field() !!}
        <div class="fullscreen">
            <div class="input-group">
                <input type="text" value="{{Request::has('search') ? Request::get('search') : ''}}"
                       class="form-control" id="search" name="search" placeholder="Search for...">
              <span class="input-group-btn">
                <button class="btn btn-info">Go!</button>
                <a class="btn btn-default" onclick="clearSearch();">CLEAR!</a>
              </span>
            </div><!-- /input-group -->
            <a href="{{url('admin/videos/create')}}" class="btn btn-info">@lang('labels.addVideos')</a>
            <div class="btn-group">
                <div class="btn-group">
                    <a class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                       aria-haspopup="true"
                       aria-expanded="false">
                        @lang('labels.videos.state' . Request::get('state', ''))
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        @foreach($states as $state)
                            <li><a href="{{url('admin/videos?state=' . $state)}}">@lang('labels.videos.state' . $state)</a> </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <table class="table">
                <thead>
                <tr>
                    <th>id</th>
                    <th>@lang('labels.originSrc')</th>
                    <th>@lang('labels.state')</th>
                    <th>@lang('labels.avatar')</th>
                    <th>@lang('labels.title')</th>
                    <th>@lang('labels.translator')</th>
                    {{--<th>@lang('labels.published')</th>--}}
                    <th>@lang('labels.level')</th>
                    <th>@lang('labels.actions')</th>
                </tr>
                </thead>
                <tbody id="tbody">
                @foreach($videos as $video)
                    <tr id="row-{{$video->id}}">
                        <td scope="row">{{$video->id}}</td>
                        <td scope="row">{{$video->originSrc}}</td>
                        <td scope="row">
                            <div class="btn-group">
                                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false" id="videoState-{{$video->id}}">
                                    @lang('labels.videos.state' . $video->state)
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach($states as $state)
                                        <li><a onclick="changeState('{{$video->id}}', '{{$state}}', '{{trans("labels.videos.state" . $state)}}')">@lang('labels.videos.state' . $state)</a> </li>
                                    @endforeach
                                </ul>
                            </div>
                        </td>
                        <td scope="row"><img src="{{$video->avatar}}" alt="" width="50px" height="50px"></td>
                        <td><a href="{{url('videos/' . $video->id)}}" TARGET="_blank">{{$video->title}}</a></td>
{{--                        <td scope="row">{{$video->publish_at->isPast() ? 1 : 0 }}</td>--}}

                        <th>{{$video->translator->name}}</th>
                        <td scope="row">
                            <div class="btn-group">
                                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false" id="videoLevel-{{$video->id}}">
                                    @lang('labels.' . $video->level)
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach($levels as $level)
                                        <li><a
                                               onclick="changeLevel('{{$video->id}}', '{{$level}}', '@lang("labels." . $level)')">@lang('labels.' . $level)</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </td>

                        <td>
                            <a class="btn btn-info"
                               href="{{url('admin/videos/' . $video->id .'/edit')}}">@lang('labels.modify')</a>
                            <a class="btn btn-default"
                               href="{{url('admin/videos/' . $video->id .'/points')}}">@lang('labels.points')</a>
                            <a class="btn btn-warning"
                               href="{{url('admin/videos/' . $video->id . '/unknown')}}">@lang('labels.unknown')</a>
                            <a class="btn btn-success"
                               href="{{url('admin/videoComments/create/' . $video->id)}}">@lang('labels.comments')</a>
                            <a class="btn btn-danger"
                               onclick="deleteContent('{{$video->id}}')">@lang('labels.delete')</a>
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
