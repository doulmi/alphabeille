@extends('admin.index')

@section('content')
    <form role="form" action="{{url('admin/' . $type . '/create/' . $content_id)}}" method="POST">
        <input type="hidden" name="_method" value="PUT"/>
        {!! csrf_field() !!}
        <div class="form-group">
            <label for="content_id">@lang('labels.contentId')</label>
            <input name='content_id' type="text" class="form-control" id="content_id" value="{{$content_id}}" />

            <label for="user_id">@lang('labels.userId')</label>
            <input name='user_id' type="text" class="form-control" id="user_id"/>

            <div class="form-group">
                <label for="content">@lang('labels.comment')</label>
                <textarea placeholder="Commentaire" class="form-control" name="content" rows="20"></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary pull-right">@lang('labels.submit')</button>
    </form>
@endsection
