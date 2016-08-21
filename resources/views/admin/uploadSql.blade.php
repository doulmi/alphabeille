

@extends('admin.index')

@section('content')
    <form method="post" action="{{url('admin/uploadSql')}}" enctype="multipart/form-data">
        {!! csrf_field() !!}
        {{--<input type="file" class="form-control" name="sql">--}}
        {{--<button>Upload</button>--}}
        <textarea class="name-input form-control" rows="20" id="content" data-provide="markdown" name="sql"></textarea>
        <div class="Header"></div>
        <button class="btn btn-primary pull-right">Upload</button>
    </form>
@endsection

@section('otherjs')
    <script src="/js/adminFullscreen.js"></script>
    <script>

    </script>

@endsection
