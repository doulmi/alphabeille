@extends('app')

@section('title')
    {{ $talkshow->title }}
@endsection

@section('content')
    <div class="body">
        <div class="Header"></div>
        <div class="Header"></div>

        @if(isset($pre))
            <div class="prePage" ><a href="{{url('talkshows/' . $pre->id )}}" class="glyphicon glyphicon-chevron-left pre-page-icon"></a></div>
        @endif

        @if(isset($next))
            <div class="nextPage">
                <a href="{{url('talkshows/' . $next->id)}}"><span class="glyphicon glyphicon-chevron-right"></span></a>
            </div>
        @endif

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
        <div class="Card-Collection">
            <div id="disqus_thread"></div>
            <script>
                /**
                 * RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
                 * LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables
                 */
                /*
                 var disqus_config = function () {
                 this.page.url = PAGE_URL; // Replace PAGE_URL with your page's canonical URL variable
                 this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
                 };
                 */
                (function() { // DON'T EDIT BELOW THIS LINE
                    var d = document, s = d.createElement('script');

                    s.src = '//alphabeille.disqus.com/embed.js';

                    s.setAttribute('data-timestamp', +new Date());
                    (d.head || d.body).appendChild(s);
                })();
            </script>
            <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
        </div>

        <div class="Header"></div>
        <div class="Header"></div>
    </div>

    @include('smallBeach')
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