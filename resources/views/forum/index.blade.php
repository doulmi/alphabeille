@extends('app')

@section('title')
    @lang('titles.forum')
@endsection

@section('content')
    <div class="body">
        <div class="Header"></div>
        <div class="Header"></div>

        <div class="container">
            <div class="row">
                @if(Session::has('success'))
                    <div class="alert alert-success">
                        {{Session::get('success')}}
                    </div>
                @elseif(Session::has('error'))
                    <div class="alert alert-danger">
                        {{Session::get('error')}}
                    </div>
                @endif
            </div>
            <div class="row">

                <ul class="discussion-list col-md-8">
                    <?php $now = \Carbon\Carbon::now(); ?>
                    @foreach($discussions as $discussion)
                        <li class="discussion-item">
                            <div class="discussion-comments-count">
                                @if($discussion->fixtop_expire_at->gt($now))
                                    {{--<i class="glyphicon glyphicon-arrow-up red"></i>--}}
                                    <span class="label label-success">@lang('labels.fixtop')</span>
                                @endif
                                <i class="glyphicon glyphicon-comment">
                                </i>
                                {{count($discussion->comments)}}
                            </div>
                            <div class="media ">
                                <a class="media-left" href="{{url('users/' . $discussion->owner->id)}}">
                                    <img src="{{$discussion->owner->avatar}}" alt="32x32"
                                         class="img-circle media-object" width="32px" height="32px">
                                </a>

                                <a class="media-body" href="{{url('discussions/' . $discussion->id)}}">
                                    <h4 class="media-heading">{{$discussion->title}}</h4>
                                    @lang('labels.updatedBy', ['name' => $discussion->lastAnswerBy->name]) {{$discussion->updated_at->diffForHumans()}}
                                </a>
                            </div>

                        </li>
                    @endforeach

                </ul>
                <div class="col-md-3 col-md-offset-1 new-discussion-panel">
                    {{--<h2>@lang('labels.conversationNum', ['num' => $count])</h2>--}}
                    <a href="{{url('discussions/create')}}" class="btn btn-lg btn-new">@lang('labels.newDiscussion')</a>
                </div>
            </div>
                <center>
                    {!! $discussions->links() !!}
                </center>
        </div>
        <div class="Header"></div>

        @include('smallBeach')
    </div>
@endsection
