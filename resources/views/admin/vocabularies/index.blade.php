@extends('admin.index')

@section('content')
  @if(Session::has('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
          aria-hidden="true">&times;</span></button>
      {{ Session::get('success')}}
    </div>
  @endif
  <div class="fullscreen">
    <a href="{{url('admin/vocabularies/create')}}" class="btn btn-info">@lang('labels.addArticle')</a>
    <table class="table">
      <thead>
      <tr>
        <th>@lang('labels.id')</th>
        <th>Hash</th>
        <th>使用于</th>
        <th>@lang('labels.actions')</th>
      </tr>
      </thead>
      <tbody id="tbody">
      @foreach($vocabularies as $article)
        <tr id="row-{{$article->id}}">
          <td>{{$article->id}}</td>
          <td><a href="{{url("vocabularies/" . $article->hash)}}" TARGET = "_blank">{{$article->hash}}</a></td>
          <td>{{$article->date}}</td>
          <td>
            <a class="btn btn-info" href="{{url('admin/vocabularies/' . $article->id .'/edit')}}">@lang('labels.modify')</a>
            <button class="btn btn-danger" onclick="deleteArticle('{{$article->id}}')">@lang('labels.delete')</button>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
    <div class="center">
      {!! $vocabularies->render() !!}
    </div>
  </div>
@endsection

@section('otherjs')
  <script src="/js/adminFullscreen.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.0/vue-resource.min.js'></script>

  <script>
    function deleteArticles(id) {
      $.ajax({
        type: "DELETE",
        url: '{{url('admin/vocabularies/')}}' + '/' + id,
        headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
        success: function (response) {
          $('#row-' + id).remove();
        }
      });
    }
  </script>
@endsection
