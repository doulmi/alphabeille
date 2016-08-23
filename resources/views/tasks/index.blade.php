@extends('app')

@section('title')@lang('labels.tasks')@endsection

@section('content')
    <div class="">
        <div class="Card-Collection">
            <div class="Header"></div>
            @if(Session::has('hasTranslator'))
                <div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    @lang('labels.hasTranslator')
                </div>
            @endif

            @if(Session::has('successSubmit'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    @lang('labels.successSubmit')
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            @lang('labels.' . Request::get('level', 'allLevel'))
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            @foreach($levels as $level)
                                <li>
                                    <a href="{{url('translator/tasks?' . (Request::has('type') ? 'type=' . Request::get('type') . '&' : '') . 'level=' . $level)}}">@lang('labels.' . $level)</a>
                                </li>
                            @endforeach
                            <li>
                                <a href="{{url('translator/tasks?' . (Request::has('type') ? 'type=' . Request::get('type') : ''))}}">@lang('labels.allLevel')</a>
                            </li>
                        </ul>
                    </div>

                    <table class="table">
                        <thead>
                        <tr>
                            <th>@lang('labels.level')</th>
                            <th>@lang('labels.avatar')</th>
                            <th>@lang('labels.title')</th>
                            <th>@lang('labels.actions')</th>
                        </tr>
                        </thead>
                        <tbody id="tbody">
                        @foreach($videos as $video)
                            <tr id="row-{{$video->id}}">
                                <td>@lang('labels.' . $video->level)</td>
                                <td scope="row"><img src="{{$video->avatar}}" alt="" width="50px" height="50px"></td>
                                <td><a href="{{url('videos/' . $video->slug)}}" TARGET="_blank">{{$video->title}}</a>
                                </td>
                                <td>
                                    @if(Auth::user()->can('videos.listen') && Request::has('type'))
                                        <a class="btn btn-info"
                                           href="{{url('translator/tasks/' . $video->id .'/checkFr')}}">@lang('labels.beFrChecker')</a>
                                    @endif
                                    @if(Auth::user()->can('videos.translate') && !Request::has('type'))
                                        <a class="btn btn-info"
                                           href="{{url('translator/tasks/' . $video->id .'/preview')}}">@lang('labels.preview')</a>
                                        <a class="btn btn-info"
                                           href="{{url('translator/tasks/' . $video->id .'/translate')}}">@lang('labels.beTranslator')</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                    @if(count($videos) < 8)
                        @for($i = 0 ; $i< 8 - count($videos); $i++)
                            <div class="Header"></div>
                        @endfor
                    @endif
                </div>
                <div class="center">
                    {!! $videos->links() !!}
                </div>
                <div class="Header"></div>
            </div>
        </div>
    </div>
@endsection

@section('otherjs')
    <script>
        function removeWord(id) {
            $.post('{{url("api/wordFav")}}/' + id + '/delete', {'_token': '{{csrf_token()}}'}, function (data) {
                $('#word-' + id).remove();
            });
        }
    </script>
@endsection