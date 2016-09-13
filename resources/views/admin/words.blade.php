@extends('admin.index')

@section('content')
        <!-- Modal -->
<style>
    .contenteditable {
        border: 1px solid #DDDDDD;
    }
</style>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
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

<div id="modifyModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">@lang('modify.modifyWord')</h4>
            </div>
            <form role="form" action="{{url('admin/words/')}}" method="POST" id="mform">
                <input type="hidden" name="_method" value="PUT"/>
                {!! csrf_field() !!}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="m-word">@lang('labels.word')</label>
                        <input name='word' type="text" class="form-control" id="m-word">
                    </div>
                    <div class="form-group">
                        <label for="m-explication">@lang('labels.explication')</label>
                        <textarea class="name-input form-control" rows="10" id="m-explication" name="explication"></textarea>
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

<form id="wordForm" action="{{url('admin/words')}}" method="GET">
    {!! csrf_field() !!}
    <div class="input-group">
        <input type="text" value="{{Request::has('search') ? Request::get('search') : ''}}"
               class="form-control" name="search" id="searchValue" placeholder="Search for...">
          <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Go!</button>
          </span>
    </div><!-- /input-group -->
</form>
    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            {{trans('labels.' . Session::get('success'))}}
        </div>
    @endif
    @if ($errors->has('email'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            @lang($errors->first('email'), ['attribute' => trans('labels.email')] )
        </div>
    @endif
    @if ($errors->has('password'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            @lang($errors->first('password'), ['attribute' => trans('labels.pwd')] )
        </div>
    @endif
    <div class="fullscreen">
        <table class="table">
            <thead>
            <tr>
                <?php
                $urls = [];
                $cols = ['id', 'word', 'explication', 'frequency', 'audio'];
                ?>
                @foreach($cols as $colName)
                    <th> @lang('labels.' . $colName) </th>
                @endforeach
                <th>@lang('labels.actions')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($words as $word)
                <tr id="row-{{$word->id}}">
                    <td scope="row">{{$word->id}}</td>
                    <td>{{$word->word}}</td>
                    <td id="word-{{$word->id}}" class="contenteditable" contenteditable="true">{!! $word->explication !!}</td>
                    <td>{{$word->frequency}}</td>
                    <td>{{$word->audio ? '有' : '无'}}</td>
                    <td>
                        <a href="#" class="btn btn-success" data-toggle="modal"
                           data-target="#modifyModal" onclick="modify({{$word->id}})">@lang('labels.modify')</a>
                        <a href="javascript:;" onclick="deleteTalkshow('{{$word->id}}')" class="btn btn-danger">@lang('labels.delete')</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="center">
            {!! $words->links() !!}
        </div>
    </div>
@endsection

@section('actions')
    <button type="button" class="btn btn-info" data-toggle="modal"
            data-target="#myModal">@lang('labels.addWord')</button>
@endsection

@section('otherjs')
    <script src="/js/adminFullscreen.js"></script>
    <script>
        function deleteTalkshow(id) {
            $.ajax({
                type: "DELETE",
                url: '{{url('admin/words/')}}' + '/' + id,
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                success: function (response) {
                    $('#row-' + id).remove();
                }
            });
        }

        $(function () {
            $('.contenteditable').blur(function () {
                var id = $(this).attr('id').substring(5);
                var content = $(this).html();
                updateWord(id, content);
            });

            function updateWord(id, content) {
                var word  = {
                    '_token': '{{csrf_token()}}',
                    'explication': content,
                    '_method': 'put'
                };

                $.post("{{url('admin/words')}}" + "/" + id, word, function (data) {});
            }
        });

        var modifyForm = $('#mform');
        var word = $('#m-word');
        var explication = $('#m-explication');
        var mid = $('#m-id');

        function modify(id) {
            var tds = $('#row-' + id).children('td');
            var _id = $(tds[0]).html();
            var _word = $(tds[1]).html();
            var _explication = $(tds[2]).html();
            var _pronounce = $(tds[3]).html();

            modifyForm.attr('action', '{{url('admin/words')}}' + '/' + id);
            mid.val(_id);
            word.val(_word);
            explication.val(_explication);
        }
    </script>
@endsection
