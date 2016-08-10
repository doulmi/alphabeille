@extends('admin.index')

@section('othercss')
    <link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css">
@endsection

@section('content')
    @if($edit)
        <form role="form" action="{{url('admin/videos/' . $video->id)}}" method="POST">
            <input type="hidden" name="_method" value="PUT"/>
            @else
                <form role="form" action="{{url('admin/videos')}}" method="POST">
                    @endif
                    {!! csrf_field() !!}

                    <div class="form-group">
                        <label for="title">@lang('labels.title')</label>
                            <input name='title' type="text" class="form-control" id="title"
                                   value="{{$edit ? $video->title : ''}}"/>
                    </div>

                    <div class="form-group">
                        <label for="avatar">@lang('labels.avatar')</label>
                            <input type="text" class="form-control" id="avatar" name="avatar"
                                   value="{{$edit ? $video->avatar : ''}}"/>
                    </div>

                    <div class="form-group">
                        <label for="video_url">@lang('labels.video_url')</label>
                            <input type="text" class="form-control" id="video_url" name="video_url"
                                   value="{{$edit ? $video->video_url : ''}}"/>
                    </div>

                    <div class="form-group">
                        <label for="keywords">@lang('labels.keywords')</label>
                            <input type="text" class="form-control" id="keywords" name="keywords"
                                   value="{{$edit ? $video->keywords : ''}}"/>
                    </div>

                    <div class="form-group">
                        <label for="free">@lang('labels.free')</label>
                            <input type="text" class="form-control" id="free" name="free" value="{{$edit ? $video->free : 0}}"/>
                    </div>

                    <div class="form-group">
                        <label for="download_url">@lang('labels.download_url')</label>
                            <input type="text" class="form-control" id="download_url" name="download_url"
                                   value="{{$edit ? $video->download_url : ''}}"/>
                    </div>

                    <div class="input-group date" id="picker">
                        <label for="showTime">@lang('labels.publish_at')</label>
                        <input class="form-control" size="16" type="text" id="showTime" name="publish_date"
                               value="{{$edit ? $video->showTime : ''}}">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        <input type="hidden" id="picker" name="publish_at"/>
                    </div>

                    <div class="form-group">
                        <label for="doPoint">@lang('labels.doPoint')</label>
                        <input type="text" class="form-control" id="doPoint" name="doPoint" value="{{$edit ? 0 : 1}}"/>
                    </div>

                    <div class="form-group">
                        <label for="content">@lang('labels.content')</label>
                            <textarea class="name-input form-control" rows="10" id="content"
                                      name="content">{{$edit ? $video->content : ''}}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="description">@lang('labels.description')</label>
                            <textarea class="name-input form-control" rows="10" id="description"
                                      name="description">{{ $edit ? $video->description : ''}}</textarea>
                    </div>

                    @endsection
                    @section('actions')
                        <button type="submit" class="btn btn-primary  pull-right">@lang('labels.submit')</button>
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
                            locale: 'fr',
                            sideBySide: true,
                            toolbarPlacement: 'bottom',
                            showClose: true,
                            ignoreReadonly: true
                        };
                        var datepicker = $('#picker');
                        datepicker.datetimepicker(datepickerConfig);

                       $('#showTime').val('{{$edit ? $video->showTime : ''}}');
                    });
                </script>
@endsection


