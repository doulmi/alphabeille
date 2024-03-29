@extends('app')

@section('title')@lang('labels.tasks' . $type)@endsection

@section('content')
    <div class="">
        <div class="container">
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
                            <?php $types = [
                                    App\Video::WAIT_CHECK_FR=> 'checkFr',
                                    App\Video::WAIT_TRANSLATE => 'translate',
                                    App\Video::WAIT_CHECK_ZH => 'checkZh'
                                  ];
                            ?>
                            @foreach($levels as $level)
                                <li>
                                    <a href="{{url('translator/tasks/' . $types[$type] .'?level=' . $level)}}">@lang('labels.' . $level)</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <table class="table">
                        <thead>
                        <tr>
                            @if(Auth::user()->isAdmin())
                            <th>@lang('labels.originSrc')</th>
                            @endif
                            <th>@lang('labels.level')</th>
                            <th>@lang('labels.avatar')</th>
                            <th>@lang('labels.title')</th>
                            @if($type == \App\Video::WAIT_CHECK_ZH)
                            <th>@lang('labels.translator')</th>
                            @endif
                            <th>@lang('labels.duration')</th>
                            <th>@lang('labels.actions')</th>
                        </tr>
                        </thead>
                        <tbody id="tbody">
                        @foreach($videos as $video)
                            <tr id="row-{{$video->id}}">
                                @if(Auth::user()->isAdmin())
                                <td>{{$video->originSrc}}</td>
                                @endif

                                <td>@lang('labels.' . $video->level)</td>
                                <td scope="row"><img src="{{$video->avatar}}" alt="" width="50px" height="50px"></td>
                                <td><a href="{{url('videos/' . $video->slug)}}" TARGET="_blank">{{$video->title}}</a> </td>

                                @if($type == \App\Video::WAIT_CHECK_ZH)
                                <td>{{$video->translator->name}}</td>
                                @endif
                                <td>{{$video->duration}}</td>
                                <td>
{{--                                    {{dd($type, \App\Video::WAIT_CHECK_FR, \App\Video::WAIT_CHECK_ZH, \App\Video::WAIT_TRANSLATE)}}--}}
                                    @if($type == \App\Video::WAIT_CHECK_FR)
                                        <a class="btn btn-info"
                                           href="{{url('translator/tasks/' . $video->id .'/checkFr')}}">@lang('labels.beFrChecker')</a>
                                    @endif

                                    @if($type == \App\Video::WAIT_TRANSLATE)
                                        <a class="btn btn-info"
                                           href="{{url('translator/tasks/' . $video->id .'/preview')}}">@lang('labels.preview')</a>
                                        <a class="btn btn-info"
                                           href="{{url('translator/tasks/' . $video->id .'/translate')}}">@lang('labels.beTranslator')</a>
                                    @endif

                                    @if($type == \App\Video::WAIT_CHECK_ZH)
                                        <a class="btn btn-info"
                                           href="{{url('translator/tasks/' . $video->id .'/checkZh')}}">@lang('labels.beZhChecker')</a>
                                    @endif
                                    @if(Auth::user()->id == 1)
                                        <a class="btn btn-info" href="{{url('admin/download/' . $video->id)}}">@lang('labels.download')</a>
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
    <div class="Header"></div>
    <div class="Header"></div>

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