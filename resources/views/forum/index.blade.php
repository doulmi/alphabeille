@extends('app')

@section('title')
    @lang('labels.forum.title')
@endsection

@section('header')
    <meta name="description" content="@lang('labels.forum.description')">
    <meta name="Keywords" content="@lang('labels.forum.keywords')">
@endsection


@section('content')
    <div class="body">
        <div class="Header"></div>

        <div class="Card-Collection">
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

                <ul class="discussion-list">
                    <li>
                        <h3>@lang('forum.list')
                        <a href="{{url('discussions/create')}}" class="pull-right btn btn-lg btn-new">@lang('labels.newDiscussion')</a>
                        </h3>
                    </li>
                    <?php $now = \Carbon\Carbon::now(); ?>
                    @foreach($discussions as $discussion)
                        <li class="discussion-item">
                            <div class="discussion-comments-count">
                                @if($discussion->fixtop_expire_at->gt($now))
                                    {{--<i class="glyphicon glyphicon-arrow-up red"></i>--}}
                                    <span class="label label-success">@lang('labels.fixtop')</span>
                                @endif
                                {{--<i class="glyphicon glyphicon-comment">--}}
                                {{--</i>--}}
                                    <i class="svg-icon svg-icon-comment">
                                        {{count($discussion->comments)}}
                                    </i>
                            </div>
                            <div class="media">
                                <a class="media-left" href="{{url('users/' . $discussion->owner->id)}}">
                                    <img data-original="{{$discussion->owner->avatar}}" src="/img/default_avatar/default_avatar.png" alt="60x60" class="img-circle media-object" width="60px" height="60px"/>
                                </a>

                                <a class="media-body" href="{{url('discussions/' . $discussion->id)}}">
                                    <h4 class="media-heading">{{$discussion->title}}</h4>
                                    @lang('labels.updatedBy', ['name' => $discussion->lastAnswerBy->name]) {{$discussion->updated_at->diffForHumans()}}
                                </a>
                            </div>

                        </li>
                    @endforeach
                </ul>

            </div>
            <center>
                {!! $discussions->links() !!}
            </center>
        </div>
        <div class="Header"></div>
        @include('smallBeach')
    </div>
@endsection

@section('otherjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>
    <script>
        $('img.img-circle').lazyload();
    </script>
@endsection
