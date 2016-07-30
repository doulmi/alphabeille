@extends('admin.index')

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


