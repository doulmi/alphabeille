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
                            <div class="progress" style="width: 0px;"></div>
                            <div class="loaded" style="width: 233.016px;"></div>
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
                            <a href="" title="Download Video" class="Button Button-with-icon ">
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
                            <a href="/series/whats-new-in-laravel-5-1">What's New in Laravel 5.1:</a>

                            Adopting PSR-2
                        </h2>

                        <p>Upon installing Laravel 5.1, the first thing you'll notice is that the style guide is a bit
                            different. Laravel now adopts the <a href="http://www.php-fig.org/psr/psr-2/">PSR-2 coding
                                standard</a>. If you're not familiar, this is nothing more than a set of style
                            guidelines
                            for writing code.</p>

                        <p class="mar-t">
                            <strong>
                                Published on Jun. 8th 2015

                                <!- The associated tool versions for the lesson ->
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