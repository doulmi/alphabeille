@extends('admin.index')

@section('othercss')
    <link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css">
@endsection

@section('content')
    @if($edit)
        <form role="form" action="{{url('admin/talkshows/' . $talkshow->id)}}" method="POST">
            <input type="hidden" name="_method" value="PUT"/>
            @else
                <form role="form" action="{{url('admin/talkshows')}}" method="POST">
                    @endif
                    {!! csrf_field() !!}

                    <div class="form-group">
                        <label for="title">@lang('labels.title')</label>
                            <input name='title' type="text" class="form-control" id="title"
                                   value="{{$edit ? $talkshow->title : ''}}"/>
                    </div>

                    <div class="form-group">
                        <label for="avatar">@lang('labels.avatar')</label>
                        <input type="text" class="form-control" id="avatar" name="avatar"
                               value="{{$edit ? $talkshow->avatar : ''}}"/>
                    </div>

                    <div class="form-group">
                        <label for="audio_url">@lang('labels.audio_url')</label>
                        <input type="text" class="form-control" id="audio_url" name="audio_url"
                                   value="{{$edit ? $talkshow->audio_url : ''}}"/>
                    </div>

                    <div class="form-group">
                        <label for="audio_url_zh_CN">@lang('labels.audio_url_zh_CN')</label>
                            <input type="text" class="form-control" id="audio_url_zh_CN" name="audio_url_zh_CN"
                                   value="{{$edit ? $talkshow->audio_url_zh_CN : ''}}"/>
                    </div>

                    <div class="form-group">
                        <label for="keywords">@lang('labels.keywords')</label>
                            <input type="text" class="form-control" id="keywords" name="keywords"
                                   value="{{$edit ? $talkshow->keywords : ''}}"/>
                    </div>

                    <div class="form-group">
                        <label for="duration">@lang('labels.duration')</label>
                        <input type="text" class="form-control" id="duration" name="duration"
                                   value="{{$edit ? $talkshow->duration : ''}}"/>
                    </div>

                    <div class="form-group">
                        <label for="free">@lang('labels.free')</label>
                            <input type="text" class="form-control" id="free" name="free" value="{{$edit ? $talkshow->free : 0}}"/>
                    </div>

                    <div class="form-group">
                        <label for="likes">@lang('labels.likes')</label>
                            <input type="text" class="form-control" id="likes" name="likes"
                                   value="{{$edit ? $talkshow->likes : ''}}" />
                    </div>

                    <div class="form-group">
                        <label for="views">@lang('labels.views')</label>
                            <input type="text" class="form-control" id="views" name="views"
                                   value="{{$edit ? $talkshow->views : ''}}"/>
                    </div>

                    <div class="form-group">
                        <label for="download_url">@lang('labels.download_url')</label>
                            <input type="text" class="form-control" id="download_url" name="download_url"
                                   value="{{$edit ? $talkshow->download_url : ''}}"/>
                    </div>

                    <div class="input-group date" id="picker">
                        <label for="showTime">@lang('labels.publish_at')</label>
                        <input class="form-control" size="16" type="text" id="showTime" name="publish_date"
                               value="{{$edit ? $talkshow->showTime : ''}}">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        <input type="hidden" id="picker" name="publish_at"/>
                    </div>

                    <div class="form-group">
                        <label for="content">@lang('labels.content')</label>
                            <textarea class="name-input form-control" rows="10" id="content"
                                      name="content">{{$edit ? $talkshow->content : ''}}</textarea>
                    </div>


                    <div class="form-group">
                        <label for="content_zh_CN">@lang('labels.content_zh_CN')</label>
                            <textarea class="name-input form-control" rows="10" id="content"
                                      name="content_zh_CN">{{$edit ? $talkshow->content_zh_CN : ''}}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="description">@lang('labels.description')</label>
                            <textarea class="name-input form-control" rows="10" id="description"
                                      name="description">{{ $edit ? $talkshow->description : ''}}</textarea>
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


