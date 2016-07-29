@extends('admin.index')

@section('content')
    @if($edit)
        <form role="form" action="{{url('admin/lessons/' . $lesson->id)}}" method="POST">
    @else
        <form role="form" action="{{url('admin/lessons')}}" method="POST">
    @endif
        {!! csrf_field() !!}

        <div class="form-group">
            <label for="topic_id">@lang('labels.topicId')</label>
            @if($edit)
                <input name='topic_id' type="text" class="form-control" id="topic_id" value="{{$lesson->topic_id}}"/>
            @else
                <input name='topic_id' type="text" class="form-control" id="topic_id" value="{{$topicId}}"/>
            @endif
        </div>

        <div class="form-group">
            <label for="title">@lang('labels.title')</label>
            @if($edit)
                <input name='title' type="text" class="form-control" id="title" value="{{$lesson->title}}"/>
            @else
                <input name='title' type="text" class="form-control" id="title"/>
            @endif
        </div>

        <div class="form-group">
            <label for="avatar">@lang('labels.avatar')</label>
            @if($edit)
                <input type="text" class="form-control" id="avatar" name="avatar" value="{{$lesson->avatar}}"/>
            @else
                <input type="text" class="form-control" id="avatar" name="avatar"/>
            @endif
        </div>

        <div class="form-group">
            <label for="audio_url">@lang('labels.audio_url')</label>
            @if($edit)
                <input type="text" class="form-control" id="audio_url" name="audio_url" value="{{$lesson->audio_url}}"/>
            @else
                <input type="text" class="form-control" id="audio_url" name="audio_url"/>
            @endif
        </div>

        <div class="form-group">
            <label for="audio_url_zh_CN">@lang('labels.audio_url_zh_CN')</label>
            @if($edit)
                <input type="text" class="form-control" id="audio_url_zh_CN" name="audio_url_zh_CN"
                       value="{{$lesson->audio_url_zh_CN}}"/>
            @else
                <input type="text" class="form-control" id="audio_url_zh_CN" name="audio_url_zh_CN"/>
            @endif
        </div>

            <div class="form-group">
                <label for="keywords">@lang('labels.keywords')</label>
                @if($edit)
                    <input type="text" class="form-control" id="keywords" name="keywords" value="{{$lesson->keywords}}"/>
                @else
                    <input type="text" class="form-control" id="keywords" name="keywords"/>
                @endif
            </div>

        <div class="form-group">
            <label for="duration">@lang('labels.duration')</label>
            @if($edit)
                <input type="text" class="form-control" id="duration" name="duration" value="{{$lesson->duration}}"/>
            @else
                <input type="text" class="form-control" id="duration" name="duration"/>
            @endif
        </div>

        <div class="form-group">
            <label for="free">@lang('labels.free')</label>
            @if($edit)
                <input type="text" class="form-control" id="free" name="free" value="{{$lesson->free}}"/>
            @else
                <input type="text" class="form-control" id="free" name="free" value="0"/>
            @endif
        </div>

        <div class="form-group">
            <label for="likes">@lang('labels.likes')</label>
            @if($edit)
                <input type="text" class="form-control" id="likes" name="likes" value="{{$lesson->likes}}"/>
                @else
                <input type="text" class="form-control" id="likes" name="likes" value="0"/>
            @endif
        </div>

        <div class="form-group">
            <label for="views">@lang('labels.views')</label>
            @if($edit)
                <input type="text" class="form-control" id="views" name="views" value="{{$lesson->likes}}"/>
            @else
                <input type="text" class="form-control" id="views" name="views" value="0"/>
            @endif
        </div>

        <div class="form-group">
            <label for="download_url">@lang('labels.download_url')</label>
            @if($edit)
                <input type="text" class="form-control" id="download_url" name="download_url"
                       value="{{$lesson->download_url}}"/>
            @else
                <input type="text" class="form-control" id="download_url" name="download_url"/>
            @endif
        </div>

        <div class="form-group">
            <label for="content">@lang('labels.content')</label>
            @if($edit)
                <textarea class="name-input form-control" rows="10" id="content"
                          name="content">{{$lesson->content}}</textarea>
            @else
                <textarea class="name-input form-control" rows="10" id="content"
                          name="content"></textarea>
            @endif
        </div>

        <div class="form-group">
            <label for="content_zh_CN">@lang('labels.content_zh_CN')</label>
            @if($edit)
                <textarea class="name-input form-control" rows="10" id="content_zh_CN"
                          name="content_zh_CN">{{ $lesson->content_zh_CN }}</textarea>
            @else
                <textarea class="name-input form-control" rows="10" id="content_zh_CN"
                          name="content_zh_CN"></textarea>
            @endif
        </div>

            <div class="form-group">
                <label for="description">@lang('labels.description')</label>
                @if($edit)
                    <textarea class="name-input form-control" rows="10" id="description"
                              name="description">{{ $lesson->description }}</textarea>
                @else
                    <textarea class="name-input form-control" rows="10" id="description"
                              name="description"></textarea>
                @endif
            </div>
        <button type="submit" class="btn btn-primary pull-right">@lang('labels.submit')</button>
    </form>
@endsection

@section('otherjs')
    <script>
        var desc = $('#description');
        var content = $('#content');
        $(function() {
            content.change(function() {
                desc.val(content.val().slice(0, 100) + '...');
            });
        });
    </script>
@endsection
