@extends('admin.index')
@section('othercss')
  <link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css">

@endsection

@section('content')
  <form role="form" action="{{url('admin/vocabularies/' . ($edit ? $vocabulary->id : ''))}}" method="POST">
    @if($edit)
      <input type="hidden" name="_method" value="PUT"/>
    @endif
    {!! csrf_field() !!}

    <div class="form-group">
      <label for="title">@lang('labels.title')</label>
      <input name='title' type="text" class="form-control" id="title"
             value="{{$edit ? $vocabulary->title: ''}}"/>
    </div>

    <div class="input-group date" id="picker">
      <label for="date">@lang('labels.publish_at')</label>
      <input class="form-control" size="16" type="text" id="date" name="date"
             value="{{$edit ? $vocabulary->date : ''}}">
      <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
      <input type="hidden" id="picker" name="date"/>
    </div>

    <div class="form-group">
      <label for="content">@lang('labels.content')</label>
      <textarea name="content" id="content" rows="10"
                data-provide="markdown">{{$edit ? $vocabulary->content: ''}}</textarea>
    </div>
    @endsection

    @section('actions')
      <button type="submit" class="btn btn-primary pull-right">@lang('labels.submit')</button>
  </form>
@endsection

@section('otherjs')
  <script src="/js/collapse.js"></script>
  <script src="/js/transition.js"></script>
  <script src="/js/moment.js"></script>
  <script src="/js/moment-with-locales.js"></script>
  <script src="/js/bootstrap-datetimepicker.min.js"></script>
  <script>
    $(function () {
      var today = new Date();
      var datepickerConfig = {
        locale: 'fr',
        sideBySide: true,
        toolbarPlacement: 'bottom',
        format: 'YYYY-MM-DD',
        showClose: true,
        ignoreReadonly: true
      };
      var datepicker = $('#picker');
      datepicker.datetimepicker(datepickerConfig);

      $('#date').val('{{$edit ? $vocabulary->date: ''}}');
    });
  </script>
@endsection
