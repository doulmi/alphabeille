@extends('admin.index')

@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            {{ Session::get('success')}}
        </div>
    @endif
    <div class="fullscreen">
        <a href="{{url('admin/videos/create')}}" class="btn btn-info">@lang('labels.addVideos')</a>
        <table class="table">
            <thead>
            <tr>
                <th>@lang('labels.title')</th>
                <th>@lang('labels.likes')</th>
                <th>@lang('labels.views')</th>
                <th>@lang('labels.free')</th>
                <th>id</th>
                <th>@lang('labels.actions')</th>
            </tr>
            </thead>
            <tbody id="tbody">
            @foreach($videos as $video)
                <tr id="row-{{$video->id}}">
                    <td>{{$video->title}}</td>
                    <td>{{$video->likes}}</td>
                    <td>{{$video->views}}</td>
                    <td>{{$video->free}}</td>
                    <th scope="row">{{$video->id}}</th>
                    <td>
{{--                        <a class="btn btn-info" href="{{url('admin/videos/' . $video->id .'/points')}}">@lang('labels.setPoint')</a>--}}
                        <a class="btn btn-info" href="{{url('admin/videos/' . $video->id .'/edit')}}">@lang('labels.modify')</a>
                        <a class="btn btn-success" href="{{url('admin/videoComments/create/' . $video->id)}}">@lang('labels.comments')</a>
                        <button class="btn btn-danger" onclick="deleteContent('{{$video->id}}')">@lang('labels.delete')</button>
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
        <div class="center">
            {!! $videos->render() !!}
        </div>
    </div>
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
    </script>

@endsection
