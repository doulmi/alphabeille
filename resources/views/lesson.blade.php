@extends('app')

@section('title')
    {{ $lesson->title }}
@endsection

@section('content')
    <div class="body">
        <div class="Header"></div>
        <div class="Header"></div>

        {{--<div class="sky">--}}
            {{--<div class="cloud variant-1"></div>--}}
            {{--<div class="cloud variant-2"></div>--}}
            {{--<div class="cloud variant-3"></div>--}}
            {{--<div class="cloud variant-4"></div>--}}
            {{--<div class="cloud variant-5"></div>--}}
            {{--<div class="cloud variant-6"></div>--}}
            {{--<div class="cloud variant-7"></div>--}}
            {{--<div class="cloud variant-8"></div>--}}
        {{--</div>--}}

        <div class="Card-Collection">
            <div class="Video-player Box">
                <div class="Video-player-wrap">
                    <audio src="https://raw.githubusercontent.com/kolber/audiojs/master/mp3/bensound-dubstep.mp3" preload="auto"></audio>
                </div>
            </div>

            <div class="Video-information">
                <div class="Video-buttons Box">
                    <ul class="utility-naked-list">
                        <li>
                            <a href="{{ url('audios/' . $lesson->id) }}"  class="Button Button-with-icon ">
                                <i class="material-icons" >cloud_download</i>
                                <span>{{ trans('labels.downloadFr') }}</span>
                            </a>
                        </li>

                        <li>
                            <a href="" class="Button Button-with-icon ">
                                <i class="material-icons">cloud_download</i>
                                <span>{{ trans('labels.downloadZh') }}</span>
                            </a>
                        </li>

                        <li>
                            <a href=""  class="Button Button-with-icon ">
                                <i class="material-icons">cloud_download</i>
                                <span>{{ trans('labels.downloadPdf') }}</span>
                            </a>
                        </li>

                        <li>
                            <a href="#disqus_thread" class="Button Button-with-icon">
                                <i class="material-icons">comment</i>
                                <span>{{ trans('labels.discuss') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="Video-details Box Box-Large hidden-xs">
                    <div class="Video-body">
                        <h2 class="mar-t-z">
                            <a href="{{url('topics/' / $lesson->topic->id) }}">{{ $lesson->topic->title }} : </a>
                            {{ $lesson->title }}
                        </h2>

                        <p class="Lesson-decription">{{ $lesson->description }}</p>

                        <p class="mar-t">
                            <strong>
                                {{trans('labels.publishOn') . ' ' . $lesson->created_at->diffForHumans()}}
                            </strong>
                        </p>

                    </div>

                    <!- The tags for the lesson ->
                </div>
            </div>
        </div>

        <div class="Header"></div>
        <h2 class="Heading-Fancy row">
            <span class="Heading-Fancy-subtitle">
                {{trans('labels.learnSlagon')}}
            </span>
            <span class='title'>{{ $topic->title }}</span>
        </h2>
        <div class="Card-Collection">
            <table class="table lessons-table table-hover">
                <tbody>
                @foreach($topic->lessons()->get() as $i => $lesson)
                    @if($lesson->id == $id )
                    <tr class="lesson-row active-row">
                    @else
                    <tr class="lesson-row">
                    @endif
                        <th scope="row">{{$i}}.</th>
                        <td onclick="window.document.location='{{url('lessons/' . $lesson->id)}}'">{{$lesson->title}}</td>
                        <td>14:32</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

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
        $(function() {
            var audio = audiojs.createAll()[0];

            $(document).keydown(function(e) {
                var unicode = e.charCode ? e.charCode : e.keyCode;
                switch(unicode) {
                    case 13:
                        audio.playPause();
                        break;
                    case 39:
                        avance(5);
                        break;
                    case 37:
                        back(5);
                        break;
                }
            });

            var avance = function(time) {
//                console.log(audio.element.currentTime);
//                console.log(audio.loadedPercent);
//                console.log(audio.duration);
//                audio.skipTo(0.8);
                audio.element.currentTime += time;
//                audio.setVolume(1);
            };

            var back = function(time) {
                audio.element.currentTime -= time;
//                audio.skipTo(0.4);
            };
        });

    </script>
@endsection