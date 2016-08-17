@extends('admin.index')

@section('content')
    <form role="form" action="{{url('admin/merge')}}" method="POST">
        {!! csrf_field() !!}
        <div class="row">
            <div class="col-md-6">

                <div class="form-group">
                    <label for="frSrc">@lang('labels.frSrc')</label>
                <textarea class="name-input form-control" rows="30" id="frSrc" data-provide="markdown"
                          name="frSrc"></textarea>
                </div>
            </div>
            <div class="col-md-6">

                <div class="form-group">
                    <label for="zhSrc">@lang('labels.zhSrc')</label>
                <textarea class="name-input form-control" rows="30" id="zhSrc" data-provide="markdown"
                          name="zhSrc"></textarea>
                </div>
            </div>
            <button class="btn btn-lg btn-primary pull-right" type="submit">合并</button>
        </div>
    </form>
@endsection
