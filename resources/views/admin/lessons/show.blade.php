@extends('admin.index')

@section('othercss')
    <link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css">
@endsection

@section('content')
    <form role="form" action="{{url('admin/lessons/' . ($edit ? $lesson->id : ''))}}" method="POST">
        {!! csrf_field() !!}

        <div class="form-group">
            <label for="topic_id">@lang('labels.topicId')</label>
            <input name='topic_id' type="text" class="form-control" id="topic_id"
                   value="{{$edit ? $lesson->topic_id : $topicId}}"/>
        </div>

        <div class="form-group">
            <label for="title">@lang('labels.title')</label>
            <input name='title' type="text" class="form-control" id="title" value="{{$edit ? $lesson->title : ''}}"/>
        </div>

        <div class="form-group">
            <label for="avatar">@lang('labels.avatar')</label>
            <input type="text" class="form-control" id="avatar" name="avatar" value="{{$edit ? $lesson->avatar : ''}}"/>
        </div>

        <div class="form-group">
            <label for="audio_url">@lang('labels.audio_url')</label>
            <input type="text" class="form-control" id="audio_url" name="audio_url"
                   value="{{$edit ? $lesson->audio_url : ''}}"/>
        </div>

        <div class="form-group">
            <label for="audio_url_zh_CN">@lang('labels.audio_url_zh_CN')</label>
            <input type="text" class="form-control" id="audio_url_zh_CN" name="audio_url_zh_CN"
                   value="{{$edit ? $lesson->audio_url_zh_CN : ''}}"/>
        </div>

        <div class="form-group">
            <label for="sex">@lang('labels.sex')[boy/girl]</label>
            <input type="text" class="form-control" id="sex" name="sex"
                   value="{{$edit ? $lesson->sex : ''}}"/>
        </div>

        <div class="form-group">
            <label for="keywords">@lang('labels.keywords')</label>
            <input type="text" class="form-control" id="keywords" name="keywords"
                   value="{{$edit ? $lesson->keywords : ''}}"/>
        </div>

        <div class="form-group">
            <label for="duration">@lang('labels.duration')</label>
            <input type="text" class="form-control" id="duration" name="duration"
                   value="{{$edit ? $lesson->duration : ''}}"/>
        </div>

        <div class="form-group">
            <label for="free">@lang('labels.free')</label>
            <input type="text" class="form-control" id="free" name="free" value="{{$edit ? $lesson->free : ''}}"/>
        </div>

        <div class="form-group">
            <label for="likes">@lang('labels.likes')</label>
            <input type="text" class="form-control" id="likes" name="likes" value="{{$edit ? $lesson->likes : ''}}"/>
        </div>

        <div class="form-group">
            <label for="views">@lang('labels.views')</label>
            <input type="text" class="form-control" id="views" name="views" value="{{$edit ? $lesson->likes : ''}}"/>
        </div>

        <div class="form-group">
            <label for="download_url">@lang('labels.download_url')</label>
            <input type="text" class="form-control" id="download_url" name="download_url"
                   value="{{$edit ? $lesson->download_url : ''}}"/>
        </div>

        <div class="input-group date" id="picker">
            <label for="showTime">@lang('labels.publish_at')</label>
            <input class="form-control" size="16" type="text" id="showTime" name="publish_date"
                   value="{{$edit ? $lesson->showTime : ''}}">
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            <input type="hidden" id="picker" name="publish_at"/>
        </div>

        <div class="form-group">
            <label for="content">@lang('labels.content')</label>
                <textarea class="name-input form-control" rows="10" id="content"
                          name="content">{{$edit ? $lesson->content : ''}}</textarea>
        </div>

        <div class="form-group">
            <label for="content_zh_CN">@lang('labels.content_zh_CN')</label>
                <textarea class="name-input form-control" rows="10" id="content_zh_CN"
                          name="content_zh_CN">{{ $edit ? $lesson->content_zh_CN : ''}}</textarea>
        </div>

        <div class="form-group">
            <label for="description">@lang('labels.description')</label>
                    <textarea class="name-input form-control" rows="10" id="description"
                              name="description">{{ $edit ? $lesson->description : ''}}</textarea>
        </div>
@endsection
@section('actions')
        <button type="submit" class="btn btn-primary pull-right">@lang('labels.submit')</button>
    </form>
@endsection

@section('otherjs')
    <script src="/js/collapse.js"></script>
    <script src="/js/transition.js"></script>
    <script src="/js/moment.js"></script>
    <script src="/js/moment-with-locales.js"></script>
    <script src="/js/bootstrap-datetimepicker.min.js"></script>
    <script>
        var desc = $('#description');
        var content = $('#content');
        $(function () {
            content.change(function () {
                desc.val(content.val().slice(0, 100) + '...');
            });

            var datepickerConfig = {
                locale: 'fr',
                sideBySide: true,
                toolbarPlacement: 'bottom',
                showClose: true,
                ignoreReadonly: true
            };
            var datepicker = $('#picker');
            datepicker.datetimepicker(datepickerConfig);

            var publishDate = $('#publishDate');
            datepicker.on("dp.change", function (e) {
                coursesDate.val($(this).data().DateTimePicker.date()._d);
                console.log($(this).data().DateTimePicker.date()._d)
            });
        });
    </script>
@endsection
