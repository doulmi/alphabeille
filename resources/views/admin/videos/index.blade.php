@extends('admin.index')

@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            {{ Session::get('success')}}
        </div>
    @endif
    <form id="videoForm" action="{{url('admin/videos')}}" method="GET" >
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
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">
                    @lang('labels.' . (Request::has('zh') ? (Request::has('desc') ? 'allVideos' : 'videosNoDesc') : 'videosNoZh'))
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="{{url('admin/videos')}}">@lang('labels.allVideos')</a></li>
                    <li><a href="{{url('admin/videos?zh=1')}}">@lang('labels.videosNoZh')</a></li>
                    <li><a href="{{url('admin/videos?desc=1')}}">@lang('labels.videosNoDesc')</a></li>
                </ul>
            </div>

            <table class="table">
                <thead>
                <tr>
                    <th>@lang('labels.avatar')</th>
                    <th>@lang('labels.title')</th>
                    <th>@lang('labels.published')</th>
                    <th>@lang('labels.originSrc')</th>
                    <th>id</th>
                    <th>@lang('labels.actions')</th>
                </tr>
                </thead>
                <tbody id="tbody">
                @foreach($videos as $video)
                    <tr id="row-{{$video->id}}">
                        <th scope="row"><img src="{{$video->avatar}}" alt="" width="50px" height="50px"></th>
                        <td><a href="{{url('videos/' . $video->id)}}" TARGET="_blank">{{$video->title}}</a></td>
                        <th scope="row">{{$video->publish_at->isPast() ? 1 : 0 }}</th>
                        <th scope="row">{{$video->originSrc}}</th>
                        <th scope="row">{{$video->id}}</th>
                        <td>
                            <a class="btn btn-info"
                               href="{{url('admin/videos/' . $video->id .'/edit')}}">@lang('labels.modify')</a>
                            <a class="btn btn-warning"
                               href="{{url('admin/videos/' . $video->id . '/unknown')}}">@lang('labels.unknown')</a>
                            <a class="btn btn-success"
                               href="{{url('admin/videoComments/create/' . $video->id)}}">@lang('labels.comments')</a>
                            <button class="btn btn-danger"
                                    onclick="deleteContent('{{$video->id}}')">@lang('labels.delete')</button>
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
    </script>

@endsection
