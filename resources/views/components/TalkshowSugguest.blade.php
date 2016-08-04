

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