@extends('app')

@section('title')
    @lang('labels.forum.title')
@endsection



@section('content')
    @include('UEditor::head')
    <div class="body">
        <div class="Header"></div>
        <div class="Header"></div>
        <div class="Card-Collection">
            <form action="{{url('discussions')}}" method="POST">
                {{csrf_field()}}

                <div class="form-group">
                    <label for="title">@lang('labels.discussTitle')</label>
                    <input type="text" class="form-control" name="title" id="title" required value="{{old('title')}}">
                </div>

                {{--<div class="form-group editor">--}}
                {{--<label for="myEditor">@lang('labels.discussContent')</label>--}}
                {{--<textarea id='myEditor' name='content'></textarea>--}}
                {{--</div>--}}
                        <!-- 加载编辑器的容器 -->
                <script id="container" style="width:100%; height: 300px" name="content" type="text/plain">
                </script>

                <!-- 实例化编辑器 -->
                <script type="text/javascript">
                    var ue = UE.getEditor('container', {
                        toolbars: [
                            ['fullscreen', 'source', 'undo', 'redo', '|', 'removeformat', 'formatmatch', 'selectall', 'cleardoc',],
                            ['bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'fontsize', '|',  'insertimage', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist'  ]
                        ]
                    });
                    ue.ready(function () {
                        ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
                    });
                </script>

                <button type="submit" class="pull-right btn btn-submit">@lang('labels.submit')</button>
            </form>
        </div>
        <div class="Header"></div>
        <div class="Header"></div>
        @include('smallBeach')
    </div>
@endsection
