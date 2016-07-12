<div class="row">
    @if(count($minitalks) == 0)
        <div class="Card-Collection search-result">
            @lang('labels.nothingCollect')
        </div>
        <div class="Header"></div>
        <div class="Header"></div>
        <div class="Header"></div>
    @else
        @foreach($minitalks as $minitalk)
            <div class="col-md-3 col-xs-6 col-sm-4">
                <div class="Card">

                    @if($minitalk->free)
                        <span class="Card-new-status Label Label-x-small">
                            @lang('labels.free')
                        </span>
                    @elseif($minitalk instanceof App\Minitalk && $minitalk->isNew())
                        <span class="Card-new-status Label Label-x-small">
                            @lang('labels.new')
                        </span>
                    @endif
                    <div class="Card-image">
                        <a href="{{ url('minitalks/' .$minitalk->id) }}">
                            <img src="/img/placeholder.png" data-original="{{$minitalk->avatar}}" class="Card-image"
                                 alt="{{$minitalk->title}}">
                            <div class="Card-overlay">
                                <i class="glyphicon glyphicon-play-circle"></i>

                            </div>
                        </a>
                    </div>
                    <div class="Card-details">
                        <h3 class="Card-title">
                            <a href="{{ url('minitalks/' . $minitalk->id) }}">{{$minitalk->title}}</a>
                        </h3>
                    </div>
                    <div class="Card-footer">
                        <div class="hidden-xs Card-footer-content">
                            <span class="topic-view">
                                <i class="svg-icon svg-icon-headphone"></i>
                                {{--                            <span class="g-font">{{ $talkshow->views }} </span>--}}
                                <span class="g-font">{{ Redis::get('minitalk:view:' . $minitalk->id ) ? : 0}} </span>
                            </span>

                            {{--<span class="topic-like">--}}
                                {{--<i class="svg-icon svg-icon-heart" id="icon-heart"></i>--}}
                                {{--<span class="glyphicon glyphicon-heart">--}}
                                {{--<span class="g-font">{{Redis::get('talkshow:like:' . $talkshow->id) ? : 0 }} </span>--}}
                                {{--</span>--}}
                            {{--</span>--}}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>