@extends('app')

@section('title')
    {{ $lesson->title }}
@endsection

@section('content')
    <div class="body">
        <div class="Header"></div>
        <div class="Header"></div>

        <div class="Card-Collection">
            <div class="Video-player Box">
                <div class="Video-player-wrap">
                    <div class="audiojs " classname="audiojs" id="audiojs_wrapper0">
                        <audio src="http://kolber.github.io/audiojs/demos/mp3/juicy.mp3" preload="auto"></audio>
                        <div class="play-pause"><p class="play"></p>
                            <p class="pause"></p>
                            <p class="loading"></p>
                            <p class="error"></p></div>
                        <div class="scrubber">
                            <div class="progress"></div>
                            <div class="loaded"></div>
                        </div>
                        <div class="time"><em class="played">00:00</em>/<strong class="duration">03:56</strong></div>
                        <div class="error-message"></div>
                    </div>
                </div>

            </div>

            <div class="Video-information">
                <div class="Video-buttons Box">
                    <ul class="utility-naked-list">
                        <li>
                            <a href="{{ url('audios/' . $lesson->id) }}" title="Download Video" class="Button Button-with-icon ">
                                <i class="material-icons">cloud_download</i>
                                <span>Download fr</span>
                            </a>
                        </li>

                        <li>
                            <a href="" title="Download Video" class="Button Button-with-icon ">
                                <i class="material-icons">cloud_download</i>
                                <span>Download ch</span>
                            </a>
                        </li>

                        <li>
                            <a href="" title="Download Video" class="Button Button-with-icon ">
                                <i class="material-icons">cloud_download</i>
                                <span>Download PDF</span>
                            </a>
                        </li>

                        <li>
                            <a href="#disqus_thread" class="Button Button-with-icon">
                                <i class="material-icons">comment</i>
                                <span>Discuss Lesson</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="Video-details Box Box-Large">
                    <div class="Video-body">
                        <h2 class="mar-t-z">
                            <a href="/series/whats-new-in-laravel-5-1">{{ $lesson->topic->title }} : </a>
                            {{ $lesson->title }}
                        </h2>

                        <p>{{ $lesson->description }}</p>

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
    </div>
@endsection

@section('otherjs')
    <script src="/js/audio.min.js"></script>
    <script>
        audiojs.events.ready(function () {
            var as = audiojs.createAll();
        });
    </script>
@endsection