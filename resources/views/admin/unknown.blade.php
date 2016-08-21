@extends('admin.index')

@section('content')
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">@lang('labels.addWord')</h4>
                </div>
                <form role="form" action="{{url('admin/words')}}" method="POST">
                    {!! csrf_field() !!}
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="word">@lang('labels.word')</label>
                            <input name='word' type="text" class="form-control" id="word">
                        </div>
                        <div class="form-group">
                            <label for="explication">@lang('labels.explication')</label>
                            <textarea class="name-input form-control" rows="10" id="explication" name="explication"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-default">@lang('labels.submit')</button>
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">@lang('labels.close')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Word</th>
                <th>@lang('labels.actions')</th>
            </tr>
        </thead>
    @foreach($words as $i => $word)
        <tr>
            <td>{{$i + 1}}</td>
            <td>{{$word}}</td>
            <td>
                <button type="button" class="btn btn-info"
                        {{--data-toggle="modal"--}}
                                onclick="showModal('{{$word}}')"
                        data-target="#myModal">@lang('labels.addWord')</button>
            </td>
        </tr>
    @endforeach
    </table>
@endsection

@section('otherjs')
    <script>
        function showModal(word) {
            $('#word').val(word);
            $('#myModal').modal('show');
        }
    </script>
@endsection

