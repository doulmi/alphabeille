@extends('app')

@section('title')@lang('labels.myTasks')@endsection

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
                    <table class="table">
                        <thead>
                        <tr>
                            <th>@lang('labels.state')</th>
                            <th>@lang('labels.avatar')</th>
                            <th>@lang('labels.title')</th>
                            <th>@lang('labels.created_at')</th>
                            <th>@lang('labels.actions')</th>
                        </tr>
                        </thead>
                        <tbody id="tbody">
                        @foreach($videos as $video)
                            <tr id="row-{{$video->id}}">
                                <td scope="row"
                                    class="{{$video->is_submit ? 'green' : 'red'}} ">{{$video->is_submit ? trans('labels.submitted') : trans('labels.onTranslate')}}</td>
                                <td scope="row"><img src="{{$video->avatar}}" alt="" width="50px" height="50px"></td>
                                <td><a href="{{url('videos/' . $video->slug)}}" TARGET="_blank">{{$video->title}}</a>
                                </td>
                                <td scope="row">{{$video->created_at}}</td>
                                <td>
                                    @if(!$video->is_submit)

                                        <a class="btn btn-info"
                                           href="{{url('translator/tasks/' . $video->video_id .'/translate')}}">@lang('labels.translate')</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                    @if(count($videos) < 8)
                        @for($i = 0 ; $i< 8 - count($videos); $i+=3)
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