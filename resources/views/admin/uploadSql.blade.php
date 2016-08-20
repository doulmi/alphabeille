<form method="post" action="{{url('admin/uploadSql')}}" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <input type="file" class="form-control" name="sql">
    <button>Upload</button>
</form>