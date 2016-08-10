@extends('admin.index')

@section('othercss')
    <link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css">
@endsection

@section('content')
    <form role="form" action="{{url('admin/minitalks/' . ($edit ? $minitalk->id : ''))}}" method="POST">
        @if($edit)
            <input type="hidden" name="_method" value="PUT"/>
        @endif
        {!! csrf_field() !!}

        <div class="form-group">
            <label for="title">@lang('labels.title')</label>
            <input name='title' type="text" class="form-control" id="title"
                   value="{{$edit ? $minitalk->title : ''}}"/>
        </div>

        <div class="form-group">
            <label for="avatar">@lang('labels.avatar')</label>
            <input type="text" class="form-control" id="avatar" name="avatar"
                   value="{{$edit ? $minitalk->avatar : ''}}"/>
        </div>

        <div class="form-group">
            <label for="audio_url">@lang('labels.audio_url')</label>
            <input type="text" class="form-control" id="audio_url" name="audio_url"
                   value="{{$edit ? $minitalk->audio_url : ''}}"/>
        </div>

        <div class="form-group">
            <label for="keywords">@lang('labels.keywords')</label>
            <input type="text" class="form-control" id="keywords" name="keywords"
                   value="{{$edit ? $minitalk->keywords : ''}}"/>
        </div>

        <div class="form-group">
            <label for="duration">@lang('labels.duration')</label>
            <input type="text" class="form-control" id="duration" name="duration"
                   value="{{$edit ? $minitalk->duration : ''}}"/>
        </div>

        <div class="form-group">
            <label for="free">@lang('labels.free')</label>
            <input type="text" class="form-control" id="free" name="free"
                   value="{{$edit ? $minitalk->free : ''}}"/>
        </div>

        <div class="form-group">
            <label for="likes">@lang('labels.likes')</label>
            <input type="text" class="form-control" id="likes" name="likes"
                   value="{{$edit ? $minitalk->likes : ''}}"/>
        </div>

        <div class="form-group">
            <label for="download_url">@lang('labels.download_url')</label>
            <input type="text" class="form-control" id="download_url" name="download_url"
                   value="{{$edit ? $minitalk->download_url : ''}}"/>
        </div>


        <div class="input-group date" id="picker">
            <label for="showTime">@lang('labels.publish_at')</label>
            <input class="form-control" size="16" type="text" id="showTime" name="publish_date"
                   value="{{$edit ? $minitalk->showTime : ''}}">
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            <input type="hidden" id="picker" name="publish_at"/>
        </div>

        <div class="form-group">
            <label for="wechat_part">@lang('labels.wechatPart')</label>
            <textarea name="wechat_part" id="wechat_part" rows="10" data-provide="markdown" >{{$edit ? $minitalk->wechat_part : ''}}</textarea>
                {{--<textarea class="name-input form-control" rows="10" id="wechat_part"--}}
{{--                          name="wechat_part">{{$edit ? $minitalk->wechat_part : ''}}</textarea>--}}
        </div>


        <div class="form-group">
            <label for="content">@lang('labels.content')</label>
                <textarea class="name-input form-control" rows="10" id="content" data-provide="markdown"
                          name="content">{{$edit ? $minitalk->content : ''}}</textarea>
        </div>

        <div class="form-group">
            <label for="description">@lang('labels.description')</label>
                <textarea class="name-input form-control" rows="10" id="description" data-provide="markdown"
                          name="description">{{$edit ? $minitalk->description : ''}}</textarea>
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
        $(function () {
            var datepickerConfig = {
                sideBySide: true,
                locale: 'fr',
                toolbarPlacement: 'bottom',
                showClose: true,
                ignoreReadonly: true
            };
            var datepicker = $('#picker');
            datepicker.datetimepicker(datepickerConfig);

            $('#showTime').val('{{$edit ? $minitalk->showTime : ''}}');
        });
    </script>
@endsection
