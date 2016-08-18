@extends('admin.index')

@section('othercss')
    <link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css">
@endsection

@section('content')
    @if($edit)
        <form role="form" target="_blank" action="{{url('admin/videos/' . $video->id)}}" method="POST" id="videoForm">
            <input type="hidden" name="_method" value="PUT"/>
            @else
                <form role="form" action="{{url('admin/videos')}}" method="POST" id="videoForm">
                    @endif
                    {!! csrf_field() !!}

                    <div class="form-group">
                        <label for="originSrc">@lang('labels.originSrc')</label>
                        <input name='originSrc' type="text" class="form-control" id="originSrc"
                               v-model="youtubeId">
                    </div>

                    <div class="form-group">
                        <label for="title">@lang('labels.title')</label>
                        <input name='title' type="text" class="form-control" id="title"
                               value="{{$edit ? $video->title : ''}}"/>
                    </div>

                    <div class="form-group">
                        <label for="avatar">@lang('labels.avatar')</label>
                        <input type="text" class="form-control" id="avatar" name="avatar"
                               v-model="jpg"/>
                    </div>

                    <div class="form-group">
                        <label for="video_url">@lang('labels.video_url')</label>
                        <input type="text" class="form-control" id="video_url" name="video_url"
                               v-model="mp4"/>
                    </div>

                    <div class="form-group">
                        <label for="keywords">@lang('labels.keywords')</label>
                        <input type="text" class="form-control" id="keywords" name="keywords"
                               value="{{$edit ? $video->keywords : ''}}"/>
                    </div>

                    <div class="form-group">
                        <label for="free">@lang('labels.free')</label>
                        <input type="text" class="form-control" id="free" name="free"
                               value="{{$edit ? $video->free : 0}}"/>
                    </div>

                    <div class="form-group">
                        <label for="download_url">@lang('labels.download_url')</label>
                        <input type="text" class="form-control" id="download_url" name="download_url"
                               v-model="mp4"/>
                    </div>

                    <div class="input-group date" id="picker">
                        <label for="showTime">@lang('labels.publish_at')</label>
                        <input class="form-control" size="16" type="text" id="showTime" name="publish_date"
                               value="{{$edit ? $video->showTime : ''}}">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        <input type="hidden" id="picker" name="publish_at"/>
                    </div>

                    {{--<div class="form-group">--}}
                    {{--<label for="doPoint">@lang('labels.doPoint')</label>--}}
                    {{--<input type="text" class="form-control" id="doPoint" name="doPoint" value="1"/>--}}
                    {{--</div>--}}

                    <div class="form-group">
                        <label>@lang('labels.level')</label>
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                @{{ level }}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                <li><a @click.stop="setLevel('beginner')">beginner</a></li>
                                <li><a @click.stop="setLevel('intermediate')">intermediate</a></li>
                                <li><a @click.stop="setLevel('advanced')">advanced</a></li>
                            </ul>
                        </div>
                        <input type="hidden" name="level" v-model="level">
                    </div>

                    <div class="form-group">
                        <label for="content">@lang('labels.content')</label>
                            <textarea class="name-input form-control" rows="10" id="content" data-provide="markdown"
                                      name="content">{{$edit ? $video->content : ''}}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="description">@lang('labels.description')</label>
                            <textarea class="name-input form-control" rows="10" id="description" data-provide="markdown"
                                      name="description">{{ $edit ? $video->description : ''}}</textarea>
                    </div>

                    <input type="hidden" name="preview" id="preview" value="0">
                    @endsection
                    @section('actions')
                        <a class="btn btn-primary pull-right" style="margin-bottom:30px"
                           onclick="preview()">@lang('labels.preview')</a>
                        <a class="btn btn-primary  pull-right" onclick="submitForm()">@lang('labels.submit')</a>
                </form>
                @endsection

            @section('otherjs')
                <script src="/js/collapse.js"></script>
                <script src="/js/transition.js"></script>
                <script src="/js/moment.js"></script>
                <script src="/js/moment-with-locales.js"></script>
                <script src="/js/bootstrap-datetimepicker.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>
                <script>
                    function preview() {
                        $('#preview').val(1);
                        var form = $('#videoForm');
                        form.prop("target", '_blank');
                        form.submit();
                    }

                    function submitForm() {
                        $('#preview').val(0);
                        var form = $('#videoForm');
                        form.prop("target", '');
                        form.submit();
                    }

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

                    new Vue({
                        el: 'body',
                        data: {
                            level: "{{$edit ? $video->level : 'beginner'}}",
                            youtubeId: "{{$edit ? $video->originSrc : ''}}"
                        },

                        computed : {
                            mp4() {
                                return 'http://o9dnc9u2v.bkt.clouddn.com/videos/' + this.youtubeId + '.mp4';
                            },

                            jpg() {
                                return 'http://o9dnc9u2v.bkt.clouddn.com/videos/' + this.youtubeId + '.jpg'
                            }
                        },

                        methods: {
                            setLevel(level) {
                                this.level = level;
                                $('.dropdown').removeClass('open');
                            }
                        }
                    })
                </script>
@endsection


