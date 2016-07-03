@extends('app')

@section('title')
    {{$topic->title}}
@endsection

@section('content')
    <div class="body">
        <div class="Header"></div>

        {{--<div class="Banner">--}}
            {{--<div class="Grid-row-flex container">--}}
                {{--<div class="Banner-thumbnail">--}}
                    {{--<img src="{{$topic->avatar}}"--}}
                         {{--alt="$topic->title">--}}
                {{--</div>--}}

                {{--<div class="utility-flex">--}}
                    {{--<h1 class="Banner-heading"> {{$topic->title}} </h1>--}}

                    {{--<div class="Banner-message">--}}
                        {{--<p>{{$topic->description}}</p>--}}

                        {{--<ul class="Banner-bullets">--}}
                            {{--<li>--}}
                                {{--<strong>{{$topic->lessonCount()}}</strong>--}}
                                {{--@lang('labels.lessons')--}}
                            {{--</li>--}}

                            {{--<li>--}}
                                {{--<strong>{{$duration}}</strong>--}}
                                {{--@lang('labels.minute')--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

        <div class="Card-Collection Banner">
            <div class="row">
                <div class="col-md-3 col-xs-offset-1 col-xs-10">
                    <a href="#" class="thumbnail">
                        <img src="{{$topic->avatar}}" alt="{{$topic->title}}"/>
                    </a>
                </div>
                <div class="col-md-7 col-xs-offset-1 col-xs-10">
                    <h1 class="Banner-heading"> {{$topic->title}} </h1>

                    <div class="Banner-message">
                        <p>{{$topic->description}}</p>

                        <ul class="Banner-bullets">
                            <li>
                                <strong>{{$topic->lessonCount()}}</strong>
                                @lang('labels.lessons')
                            </li>

                            <li>
                                <strong>{{$duration}}</strong>
                                @lang('labels.minute')
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="Heading-Fancy row">
            <span class="Heading-Fancy-subtitle">
                @lang('labels.learnSlagon')
            </span>
            <span class='title'>{{ $topic->title }}</span>
        </h2>

        @include('lessons.lessonsList')

        <div class="Header"></div>
    </div>

    {{--    @include('utils.topicSugguest')--}}

@endsection


