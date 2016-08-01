@extends('admin.index')

@section('othercss')
    {{--<link rel="stylesheet" href="/css/classic.css">--}}
    {{--<link rel="stylesheet" href="/css/classic.date.css">--}}
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
                @if($edit)
                    <input name='title' type="text" class="form-control" id="title"
                           value="{{$video->title}}"/>
                @else
                    <input name='title' type="text" class="form-control" id="title"/>
                @endif
            </div>

            <div class="form-group">
                <label for="avatar">@lang('labels.avatar')</label>
                @if($edit)
                    <input type="text" class="form-control" id="avatar" name="avatar"
                           value="{{$video->avatar}}"/>
                @else
                    <input type="text" class="form-control" id="avatar" name="avatar"/>
                @endif
            </div>

            <div class="form-group">
                <label for="video_url">@lang('labels.video_url')</label>
                @if($edit)
                    <input type="text" class="form-control" id="video_url" name="video_url"
                           value="{{$video->video_url}}"/>
                @else
                    <input type="text" class="form-control" id="video_url" name="video_url"/>
                @endif
            </div>

            <div class="form-group">
                <label for="keywords">@lang('labels.keywords')</label>
                @if($edit)
                    <input type="text" class="form-control" id="keywords" name="keywords"
                           value="{{$video->keywords}}"/>
                @else
                    <input type="text" class="form-control" id="keywords" name="keywords"/>
                @endif
            </div>

            <div class="form-group">
                <label for="free">@lang('labels.free')</label>
                @if($edit)
                    <input type="text" class="form-control" id="free" name="free" value="{{$video->free}}"/>
                @else
                    <input type="text" class="form-control" id="free" name="free" value="0"/>
                @endif
            </div>

            <div class="form-group">
                <label for="download_url">@lang('labels.download_url')</label>
                @if($edit)
                    <input type="text" class="form-control" id="download_url" name="download_url"
                           value="{{$video->download_url}}"/>
                @else
                    <input type="text" class="form-control" id="download_url" name="download_url"/>
                @endif
            </div>

            <div class="input-group date" id="picker">
                <label for="showTime">@lang('labels.publish_at')</label>
                <input class="form-control" size="16" type="text" id="showTime" name="publish_date">
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                <input type="hidden" id="picker" name="publish_at"/>
            </div>

            <div class="form-group">
                <label for="content">@lang('labels.content')</label>
                @if($edit)
                    <textarea class="name-input form-control" rows="10" id="content"
                              name="content" >{{$video->content}}</textarea>
                @else
                    <textarea class="name-input form-control" rows="10" id="content"
                              name="content" ></textarea>
                @endif
            </div>

            <div class="form-group">
                <label for="description">@lang('labels.description')</label>
                @if($edit)
                    <textarea class="name-input form-control" rows="10" id="description"
                              name="description">{{ $video->description }}</textarea>
                @else
                    <textarea class="name-input form-control" rows="10" id="description"
                              name="description"></textarea>
                @endif
            </div>


@endsection
            @section('actions')
            <button type="submit" class="btn btn-primary  pull-right">@lang('labels.submit')</button>
            @endsection
        </form>

@section('otherjs')
        <script src="/js/collapse.js"></script>
        <script src="/js/transition.js"></script>
        <script src="/js/moment.js"></script>
        <script src="/js/moment-with-locales.js"></script>
        <script src="/js/bootstrap-datetimepicker.min.js"></script>
        <script>
            var desc = $('#description');
            var content = $('#content');
            $(function() {
                content.change(function() {
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
//                var $input = $('.datepicker').pickadate({
//                    formatSubmit: 'yyyy/mm/dd',
//                    container: '#container',
//                    closeOnSelect: true,
//                    closeOnClear: false,
//                    today: true
//                });
//
//                var picker = $input.pickadate('picker');
//                picker.set('select', today)
            });
        </script>
@endsection


