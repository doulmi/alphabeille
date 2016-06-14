@extends('app')

@section('title')
    {{ $lesson->title }}
@endsection
@section('content')
    <div class="body">
        <div class="Header"></div>
        <div class="Header"></div>
        <div class="Card-Collection">
            <h2 class="mar-t-z center">
                {{ $lesson->title }}
            </h2>
            @if($lesson->free || (!Auth::guest() && Auth::user()->level() > 1))
                <audio id='audio' preload="auto" controls hidden>
                    <source src="https://raw.githubusercontent.com/kolber/audiojs/master/mp3/bensound-dubstep.mp3"/>
                </audio>
            @else
                @include('blockContent')
            @endif

            <div class="Video-information row">
                <div class="Video-buttons Box">
                    <ul class="utility-naked-list ">
                        @if($lesson->free || (!Auth::guest() && Auth::user()->level() > 1))
                        <li>
                            <a href="{{ url('audios/' . $lesson->id) }}" class="Button-with-icon">
                                <i class="glyphicon glyphicon-download-alt"></i>
                                <span>@lang('labels.download') </span>
                            </a>
                        </li>
                        @endif

                        <li>
                            <a href="#disqus_thread" class="Button-with-icon">
                                <i class="glyphicon glyphicon-comment"></i>
                                <span>@lang('labels.discuss') </span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="Video-details Box Box-Large">
                    <div class="Video-body">
                        <p class="Lesson-decription">{{ $lesson->description }}</p>
                        <p class="mar-t">
                            <strong>
                                {{trans('labels.publishOn') . ' ' . $lesson->created_at->diffForHumans()}}
                            </strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="Header"></div>
        <h2 class="Heading-Fancy row">
            <span class="Heading-Fancy-subtitle">
                @lang('labels.learnSlagon')
            </span>
            <span class='title'>{{ $topic->title }}</span>
        </h2>

        <div class="Card-Collection">
            <table class="table lessons-table table-hover">
                <tbody>
                @foreach($topic->lessons()->get() as $i => $les)
                    @if($les->id == $lesson->id)
                        <tr class="lesson-row active-row">
                    @else
                        <tr class="lesson-row">
                            @endif
                            <th scope="row">{{$i + 1}}.</th>
                            <td onclick="window.document.location='{{url('lessons/' . $les->id)}}'">
                                {{$les->title}}
                                @if($les->free)
                                    <span class="free-label">@lang('labels.free')</span>
                                @endif
                            </td>
                            <td>14:32</td>
                        </tr>
                        @endforeach
                </tbody>
            </table>
        </div>

{{--        @include('sugguest')--}}
        <div class="Header"></div>
        <h2 class="Heading-Fancy row">
            <span class='title'>{{ trans('labels.suggestTopics')}}</span>
        </h2>

        <div class="Card-Collection">
            <div class="row">
                @foreach($topics as $topic)
                    <div class="col-md-3 col-xs-6 col-sm-4">
                        <div class="Card">
                    <span class="Card-difficulty">
                        {{ trans('labels.' . $topic->level) }}
                    </span>

                            @if($topic->isNew)
                                <span class="Card-new-status Label Label-x-small">
                        {{trans('labels.new')}}
                    </span>
                            @elseif($topic->isUpdated)
                                <span class="Card-updated-status Label Label-x-small">
                        {{ trans('labels.updated') }}
                    </span>
                            @endif
                            <div class="Card-image">
                                <a href="{{ url('topics/' . $topic->id) }}">
                                    <img src="{{$topic->avatar}}" class="Card-image" alt="{{$topic->title}}">
                                    <div class="Card-overlay">
                                        <i class="glyphicon glyphicon-play-circle"></i>
                                    </div>
                                </a>
                            </div>
                            <div class="Card-details">
                                <h3 class="Card-title">
                                    <a href="{{ url('/topics/' . $topic->id) }}">{{$topic->title}}</a>
                                </h3>
                                <div class="Card-count">{{ $topic->lessonCount() }} <span
                                            class="utility-muted"> {{trans('labels.lessons')}}</span>
                                </div>
                            </div>
                            <div class="Card-footer">
                                <div class="hidden-xs Card-footer-content">
                        <span class="topic-view">
                            <span class="glyphicon glyphicon-eye-open"><span
                                        class="g-font">{{ $topic->views() }} </span></span>
                        </span>

                        <span class="topic-like">
                            <span class="glyphicon glyphicon-heart"><span
                                        class="g-font">{{ $topic->likes() }} </span></span>
                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="Header"></div>
    </div>


    <div class="small-beach">
        <ul>
            <li class="layer umbra"><img src="/img/umbra.png"></li>
            <li class="layer cloud1"><img src="/img/cloud1.png"></li>
            <li class="layer cloud2"><img src="/img/cloud2.png"></li>
        </ul>
    </div>
@endsection

@section('otherjs')
    <script src="/js/audio.min.js"></script>
    <script>
        var isIE = function (ver) {
            var b = document.createElement('b')
            b.innerHTML = '<!--[if IE ' + ver + ']><i></i><![endif]-->'
            return b.getElementsByTagName('i').length === 1
        };

        if (isIE(6) || isIE(7) || isIE(8)) {

        }

        $(function () {
            var audios = $('#audio');
            audios.audioPlayer();


//            var audio = document.querySelector('#audio');
//
//            $(document).keydown(function (e) {
//                var unicode = e.charCode ? e.charCode : e.keyCode;
//                switch (unicode) {
//                    case 13:
//                        break;
//                    case 39:
//                        break;
//                    case 37:
//                        break;
//                }
//            });
//
//            var avance = function (time) {
//                audio.element.currentTime += time;
//            };
//
//            var back = function (time) {
//                audio.element.currentTime -= time;
//            };
        });


    </script>
@endsection