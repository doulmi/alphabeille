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
        <table class="table">
            <thead>
            <tr>
                <th>@lang('labels.avatar')</th>
                <th>@lang('labels.title')</th>
                <th>@lang('labels.likes')</th>
                <th>@lang('labels.views')</th>
                <th>@lang('labels.free')</th>
                <th>id</th>
                <th>@lang('labels.actions')</th>
            </tr>
            </thead>
            <tbody id="tbody">
            @foreach($lessons as $lesson)
                <tr id="row-{{$lesson->id}}">
                    <td><img width="60px" height="60px" src="{{$lesson->avatar}}" alt=""></td>
                    <td><a href="{{url("lessons/" . $talkshow->id)}}" TARGET = "_blank">{{$lesson->title}}</a></td>
                    <td>{{$lesson->likes}}</td>
                    <td>{{$lesson->views}}</td>
                    <td>{{$lesson->free}}</td>
                    <th scope="row">{{$lesson->id}}</th>
                    <td>
                        <button class="btn btn-danger"
                                onclick="deleteLesson('{{$lesson->id}}')">@lang('labels.delete')</button>
                        <a class="btn btn-info" href="{{url('admin/lessons/' . $lesson->id .'/edit')}}">@lang('labels.modify')</a>
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
        function deleteLesson(id) {
            $.ajax({
                type: "DELETE",
                url: '{{url('admin/lessons/')}}' + '/' + id,
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                success: function (response) {
                    $('#row-' + id).remove();
                }
            });
        }
    </script>

@endsection
