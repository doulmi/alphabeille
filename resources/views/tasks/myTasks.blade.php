@extends('app')

@section('title')@lang('labels.myTasks')@endsection

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
                    <table class="table">
                        <thead>
                        <tr>
                            @if(Auth::user()->isAdmin())
                                <th>@lang('labels.originSrc')</th>
                            @endif
                            <th>@lang('labels.state')</th>
                            <th>@lang('labels.avatar')</th>
                            <th style="width:50%">@lang('labels.title')</th>
                            <th>@lang('labels.created_at')</th>
                            <th>@lang('labels.actions')</th>
                        </tr>
                        </thead>
                        <tbody id="tbody">
                        @foreach($videos as $video)
                            <tr id="row-{{$video->id}}">
                                @if(Auth::user()->isAdmin())
                                    <td>{{$video->originSrc}}</td>
                                @endif
                                <td scope="row"
                                    class="state-color{{$video->state}}">@lang('labels.videos.state' . $video->state)</td>
                                <td scope="row"><img src="{{$video->avatar}}" alt="" width="50px" height="50px"></td>
                                <td><a href="{{url('videos/' . $video->slug)}}" TARGET="_blank">{{$video->title}}</a>
                                </td>
                                <td scope="row">{{$video->created_at}}</td>
                                <td>
                                    @if(!$video->is_submit)
                                        @if(str_contains(Request::getRequestUri(), 'checkFr'))
                                            <a class="btn btn-info"
                                               href="{{url('translator/tasks/' . $video->video_id .'/checkFr')}}">@lang('labels.beFrChecker')</a>
                                        @elseif(str_contains(Request::getRequestUri(), 'translate'))
                                            <a class="btn btn-info"
                                               href="{{url('translator/tasks/' . $video->video_id .'/translate')}}">@lang('labels.translate')</a>

                                            <a class="btn btn-danger"
                                               href="{{url('translator/tasks/' . $video->id.'/giveupTranslate')}}">@lang('labels.giveup')</a>
                                        @else
                                            <a class="btn btn-info"
                                               href="{{url('translator/tasks/' . $video->video_id .'/checkZh')}}">@lang('labels.verifier')</a>

                                            <a class="btn btn-danger"
                                               href="{{url('translator/tasks/' . $video->id.'/giveupCheckZh')}}">@lang('labels.giveup')</a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="center">
                    {!! $videos->links() !!}
                </div>
            </div>

            <div class="Header"></div>
            <div class="Header"></div>
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