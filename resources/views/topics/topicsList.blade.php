<div class="row">
    @foreach($topics as $topic)
        <div class="col-md-3 col-xs-6 col-sm-4">
            <div class="Card card">
                    <span class="Card-difficulty">
                        @lang('labels.' . $topic->level)
                    </span>

                @if($topic->isNew)
                    <span class="Card-new-status Label Label-x-small">
                        @lang('labels.new')
                    </span>
                @elseif($topic->isUpdated)
                    <span class="Card-updated-status Label Label-x-small">
                        @lang('labels.updated')
                    </span>
                @endif
                <div class="Card-image">
                    <a href="{{ url('topics/' . $topic->id) }}">
                        <img src="/img/placeholder.png" data-original="{{$topic->avatar}}" class="Card-image" alt="{{$topic->title}}">
                        <div class="Card-overlay">
                            <i class="glyphicon glyphicon-play-circle"></i>
                        </div>
                    </a>
                </div>
                <div class="Card-details">
                    <h3 class="Card-title">
                        <a href="{{ url('/topics/' . $topic->id) }}">{{$topic->title}}</a>
                    </h3>
                    <div class="Card-count">{{ $topic->lessonCount() }} <span
                                class="utility-muted"> @lang('labels.lessons')</span>
                    </div>
                </div>
                <div class="Card-footer">
                    <div class="hidden-xs Card-footer-content">
                        <span class="topic-view">
                            <span class="glyphicon glyphicon-eye-open"><span
                                        class="g-font">{{ $topic->views() }} </span></span>
                        </span>

                        <span class="topic-like">
                            {{--<span class="glyphicon glyphicon-heart">--}}
                               <i class="svg-icon svg-icon-heart" id="icon-heart"></i>
                                <span class="g-font">{{ $topic->likes() }} </span>
                            {{--</span>--}}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>