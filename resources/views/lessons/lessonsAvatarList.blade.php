<div class="row">
    @if(count($lessons) == 0)
        <div class="Card-Collection search-result">
            @lang('labels.nothingCollect')
        </div>
        <div class="Header"></div>
        <div class="Header"></div>
        <div class="Header"></div>
    @else
        @foreach($lessons as $lesson)
            <div class="col-md-3 col-xs-6 col-sm-4">
                <div class="Card">
                    @if($lesson->free)
                        <span class="Card-new-status Label Label-x-small">
                        @lang('labels.free')
                    </span>
                    @elseif($lesson instanceof App\Lesson && $lesson->isNew())
                        <span class="Card-new-status Label Label-x-small">
                        @lang('labels.new')
                    </span>
                    @endif
                    <div class="Card-image">
                        <a href="{{ url('lessons/' . $lesson->slug) }}" title="{{$lesson->title}}">
                            <img src="/img/placeholder.png" data-original="{{$lesson->avatar}}" class="Card-image"
                                 alt="{{$lesson->title}}">
                            <div class="Card-overlay">
                                <i class="glyphicon glyphicon-play-circle"></i>
                            </div>
                        </a>
                    </div>
                    <div class="Card-details">
                        <h3 class="Card-title">
                            <a href="{{ url('lessons/' . $lesson->slug) }}" title="{{$lesson->title}}">{{$lesson->title}}</a>
                        </h3>
                    </div>
                    <div class="Card-footer">
                        <div class="hidden-xs Card-footer-content">
                        <span class="topic-view">
                            <i class="svg-icon svg-icon-headphone"></i>
                            <span class="g-font">{{ Redis::get('lesson:view:' . $lesson->id) }} </span>
                        </span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>