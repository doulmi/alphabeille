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
                        <span class="Card-new-status Label Label-x-small">
                            @lang('labels.new')
                        </span>
                    @endif
                    <div class="Card-image">
                        <a href="{{ url('readables/' .$readable->slug) }}">
                            <img src="http://o9dnc9u2v.bkt.clouddn.com/images/holder.jpg" data-original="{{$readable->avatar}}" class="Card-image"
                                 alt="{{$readable->title}}">
                            <div class="Card-overlay">
                                <i class="glyphicon glyphicon-play-circle"></i>
                            </div>
                        </a>
                    </div>
                    <div class="Card-details">
                        <h3 class="Card-title">
                            <a href="{{ url('readables/' . $readable->slug) }}">{{$readable->title}}</a>
                        </h3>
                    </div>
                    <div class="Card-footer">
                        <div class="hidden-xs Card-footer-content">
                            <span class="topic-view">
                                <i class="glyphicon glyphicon-headphones "></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>