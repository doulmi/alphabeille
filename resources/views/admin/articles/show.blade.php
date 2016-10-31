@extends('admin.index')

@section('content')
    <form role="form" action="{{url('admin/articles/' . ($edit ? $article->id : ''))}}" method="POST">
        @if($edit)
            <input type="hidden" name="_method" value="PUT"/>
        @endif
        {!! csrf_field() !!}

        <div class="form-group">
            <label for="reciteAt">@lang('labels.reciteAt')</label>
            <input name='reciteAt' type="text" class="form-control" id="reciteAt"
                   value="{{$edit ? $article->reciteAt : ''}}"/>
        </div>

        <div class="form-group">
            <label for="type">@lang('labels.type')</label>
            <input type="text" class="form-control" id="type" name="type"
                   value="{{$edit ? $article->type : ''}}"/>
        </div>

        <div class="form-group">
            <label for="limitTime">@lang('labels.limitTime')</label>
            <input type="text" class="form-control" id="limitTime" name="limitTime"
                   value="{{$edit ? $article->limitTime : ''}}"/>
        </div>

        <div class="form-group">
            <label for="url">@lang('labels.audio_url')</label>
            <input type="text" class="form-control" id="url" name="url"
                   value="{{$edit ? $article->url : ''}}"/>
        </div>

        <div class="form-group">
            <label for="content">@lang('labels.content')</label>
            <textarea name="content" id="content" rows="10"
                      data-provide="markdown">{{$edit ? $article->content: ''}}</textarea>
        </div>
        @endsection

        @section('actions')
            <button type="submit" class="btn btn-primary pull-right">@lang('labels.submit')</button>
    </form>
@endsection
