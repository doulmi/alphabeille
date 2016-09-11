<div class="row">
    @if(count($readables) == 0)
        <div class="Card-Collection search-result">
            @lang('labels.nothingCollect')
        </div>
        <div class="Header"></div>
        <div class="Header"></div>
        <div class="Header"></div>
    @else
        @foreach($readables as $readable)
            <div class="col-md-3 col-xs-6 col-sm-4">
                <div class="Card">
                    @if($readable->free)
                        <span class="Card-new-status Label Label-x-small">
                            @lang('labels.free')
                        </span>
                    @elseif($readable->isNew())
                        <span class="Card-updated-status Label Label-x-small">
                        @lang('labels.new')
                        </span>
                    @endif
                    <div class="Card-image">
                        <a href="{{ url($type . 's/' .$readable->slug) }}">
                            <img src="http://o9dnc9u2v.bkt.clouddn.com/images/holder.jpg"
                                 data-original="{{$readable->avatar}}" class="Card-image"
                                 alt="{{$readable->title}}">
                            <div class="Card-overlay">
                                <i class="glyphicon glyphicon-play-circle"></i>
                            </div>
                        </a>
                    </div>
                    <div class="Card-details">
                        <h3 class="Card-title">
                            <a href="{{ url($type . 's/' . $readable->slug) }}">{{$readable->title}}</a>
                        </h3>
                    </div>
                    <div class="Card-footer">
                        <div class="Card-footer-content">
                            <span class="topic-view">
                                <i class="glyphicon glyphicon-headphones"></i>
{{--                                <span class="g-font">{{ Redis::get($type . ':view:' . $readable->id) }}</span>--}}
                                    <span class="g-font">{{ $readable->views }}</span>
                            </span>
                            @if($readable instanceof \App\Video)
                                <div class="video-tags">
                                    <span class="Card-difficulty {{$readable->level}} tooltips-top" data-tooltips="@lang('labels.' . $readable->level)">
                                        @lang('labels.' . $readable->level . '_abbr')
                                    </span>
                                    @if($readable->state == 5 || $readable->state == 6)
                                        <span class="Card-difficulty chinese tooltips-top" data-tooltips="@lang('labels.zh_CN')">
                                        @lang('labels.zh_CN_abbr')
                                        </span>
                                    @endif
                                    @if($readable->state == 6)
                                        <span class="Card-difficulty desc tooltips-top" data-tooltips="@lang('labels.notation')">
                                        @lang('labels.notation_abbr')
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>