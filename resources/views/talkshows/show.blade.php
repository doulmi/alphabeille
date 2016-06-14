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


        <div class="Header"></div>
        <h2 class="Heading-Fancy row">
            <span class='title'>{{ trans('labels.suggestTalkshows')}}</span>
        </h2>
        <div class="Card-Collection">
            <div class="row">
                @foreach($talkshows as $talkshow)
                    <div class="col-md-3 col-xs-6 col-sm-4">
                        <div class="Card">

                            @if($talkshow->isNew())
                                <span class="Card-new-status Label Label-x-small">
                        {{trans('labels.new')}}
                    </span>
                            @endif
                            <div class="Card-image">
                                <a href="{{ url('talkshows/' .$talkshow->id) }}">
                                    <img src="{{$talkshow->avatar}}" class="Card-image" alt="{{$talkshow->title}}">
                                    <div class="Card-overlay">
                                        <i class="glyphicon glyphicon-play-circle"></i>

                                    </div>
                                </a>
                            </div>
                            <div class="Card-details">
                                <h3 class="Card-title">
                                    <a href="{{ url('talkshows/' . $talkshow->id) }}">{{$talkshow->title}}</a>
                                </h3>
                            </div>
                            <div class="Card-footer">
                                <div class="hidden-xs Card-footer-content">
                        <span class="topic-view">
                            <span class="glyphicon glyphicon-eye-open"><span
                                        class="g-font">{{ $talkshow->views }} </span></span>
                        </span>

                        <span class="topic-like">
                            <span class="glyphicon glyphicon-heart"><span
                                        class="g-font">{{ $talkshow->likes }} </span></span>
                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
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
        $(function () {
            var audios = $('#audio');
            audios.audioPlayer();
        });

    </script>
@endsection