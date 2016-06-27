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
        <a href="{{url('admin/talkshows/create')}}" class="btn btn-info">@lang('labels.addTalkshow')</a>
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
            @foreach($talkshows as $talkshow)
                <tr id="row-{{$talkshow->id}}">
                    <td><img width="60px" height="60px" src="{{$talkshow->avatar}}" alt=""></td>
                    <td>{{$talkshow->title}}</td>
                    <td>{{$talkshow->likes}}</td>
                    <td>{{$talkshow->views}}</td>
                    <td>{{$talkshow->free}}</td>
                    <th scope="row">{{$talkshow->id}}</th>
                    <td>
                        <button class="btn btn-danger"
                                onclick="deleteTalkshow('{{$talkshow->id}}')">@lang('labels.delete')</button>
                        <a class="btn btn-info" href="{{url('admin/talkshows/' . $talkshow->id .'/edit')}}">@lang('labels.modify')</a>
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
        function deleteTalkshow(id) {
            $.ajax({
                type: "DELETE",
                url: '{{url('admin/talkshows/')}}' + '/' + id,
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                success: function (response) {
                    $('#row-' + id).remove();
                }
            });
        }
    </script>

@endsection
