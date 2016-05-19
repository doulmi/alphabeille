@extends('app')

@section('title')
    {{ $talkshow->title }}
@endsection

@section('content')
    <div class="body">
        <div class="Header"></div>
        <div class="Header"></div>


        <div class="Card-Collection">
            <h2 class="mar-t-z center">
                {{ $talkshow->title }}
            </h2>

            <audio id='audio' preload="auto" controls hidden>
                <source src="https://raw.githubusercontent.com/kolber/audiojs/master/mp3/bensound-dubstep.mp3"/>
            </audio>

            <div class="Video-information row">
                <div class="Video-buttons Box">
                    <ul class="utility-naked-list ">
                        <li>
                            <a href="{{ url('audios/' . $talkshow->id) }}" class="Button-with-icon">
                                <i class="glyphicon glyphicon-download-alt"></i>
                                <span>@lang('labels.download')</span>
                            </a>
                        </li>

                        <li>
                            <a href="#disqus_thread" class="Button-with-icon">
                                <i class="glyphicon glyphicon-comment"></i>
                                <span> @lang('labels.discuss') </span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="Video-details Box Box-Large">
                    <div class="Video-body">
                        <p class="Lesson-decription">{{ $talkshow->description }}</p>
                        <p class="mar-t">
                            <strong>
                                @lang('labels.publishOn')  {{' ' . $talkshow->created_at->diffForHumans()}}
                            </strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @include('sugguest')

        <div class="Header"></div>
        <div class="Header"></div>
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
        $(function () {
            var audios = $('#audio');
            audios.audioPlayer();
//
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