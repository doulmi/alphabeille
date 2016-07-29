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
        <a href="{{url('/admin/comments/lesson')}}" class="btn btn-primary">@lang('labels.lessonComment')</a>
        <a href="{{url('/admin/comments/minitalk')}}" class="btn btn-success">@lang('labels.minitalkComment')</a>
        <a href="{{url('/admin/comments/talkshow')}}" class="btn btn-info">@lang('labels.talkshowComment')</a>
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>user</th>
                <th>content</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody id="tbody">
            @foreach($comments as $comment)
                <tr id="row-{{$comment->id}}">
                    <td>{{$comment->id}}</td>
                    <td>{{$comment->user_id}}</td>
                    <td>{{$comment->content}}</td>
                    <td>
                        <button class="btn btn-danger" onclick="deleteComment('{{$comment->id}}')">@lang('labels.delete')</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="center">
            {!! $comments->render() !!}
        </div>
    </div>
@endsection

@section('otherjs')
    <script src="/js/adminFullscreen.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.0/vue-resource.min.js'></script>

    <script>
        function deleteComment(id) {
            $.ajax({
                type: "DELETE",
                url: '{{url('admin/comments/' . $type )}}' + '/' + id,
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                success: function (response) {
                    $('#row-' + id).remove();
                }
            });
        }
    </script>

@endsection
