<div class="row">
    @if(count($talkshows) == 0)
        <div class="Card-Collection search-result">
            @lang('labels.nothingCollect')
        </div>
        <div class="Header"></div>
        <div class="Header"></div>
        <div class="Header"></div>
    @else
        @foreach($talkshows as $talkshow)
            <div class="col-md-col-xs-6 col-sm-4">
                <div class="Card">

                    @if($talkshow->free)
                        <span class="Card-new-status Label Label-x-small">
                            @lang('labels.free')
                        </span>
                    @elseif($talkshow instanceof App\Talkshow && $talkshow->isNew())
                        <span class="Card-new-status Label Label-x-small">
                            @lang('labels.new')
                        </span>
                    @endif
                    <div class="Card-image">
                        <a href="{{ url('talkshows/' .$talkshow->slug) }}">
                            <img src="http://o9dnc9u2v.bkt.clouddn.com/images/holder.jpg" data-original="{{$talkshow->avatar}}" class="Card-image"
                                 alt="{{$talkshow->title}}">
                            <div class="Card-overlay">
                                <i class="glyphicon glyphicon-play-circle"></i>

                            </div>
                        </a>
                    </div>
                    <div class="Card-details">
                        <h3 class="Card-title">
                            <a href="{{ url('talkshows/' . $talkshow->slug) }}">{{$talkshow->title}}</a>
                        </h3>
                    </div>
                    <div class="Card-footer">
                        <div class="hidden-xs Card-footer-content">
                            <span class="topic-view">
                               <i class="glyphicon glyphicon-headphones "></i>
                                <span class="g-font">{{ Redis::get('talkshow:view:' . $talkshow->id ) ? : 0}} </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>