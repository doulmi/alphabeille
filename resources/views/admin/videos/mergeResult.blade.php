@extends('admin.index')

@section('content')
    <div class="row">
        <div class="form-group">
            <label for="frSrc">@lang('labels.frSrc')</label>
            <textarea class="name-input form-control" rows="30" id="frSrc" data-provide="markdown" name="frSrc">{{$mergedSub}}</textarea>
        </div>
    </div>
@endsection
