<div class="row">
    @foreach($topics as $topic)
        <div class="col-md-3 col-xs-6 col-sm-4">
            <div class="Card">
                    <span class="Card-difficulty">
                        {{ trans('labels.' . $topic->level) }}
                    </span>

                @if($topic->isNew())
                    <span class="Card-new-status Label Label-x-small">
                        {{trans('labels.new')}}
                    </span>
                @elseif($topic->isUpdated())
                    <span class="Card-updated-status Label Label-x-small">
                        {{ trans('labels.updated') }}
                    </span>
                @endif
                <div class="Card-image">
                    <a href="{{ url('topics/' . $topic->id) }}">
                        <img src="{{$topic->avatar}}" class="Card-image" alt="{{$topic->title}}">
                        <div class="Card-overlay">
                            <i class="material-icons">play_circle_outline</i>
                        </div>
                    </a>
                </div>
                <div class="Card-details">
                    <h3 class="Card-title">
                        <a href="{{ url('/topics/') . $topic->id }}">{{$topic->title}}</a>
                    </h3>
                    <div class="Card-count">{{ $topic->lessonCount() }} <span
                                class="utility-muted"> {{trans('labels.lessons')}}</span>
                    </div>
                </div>
                <div class="Card-footer">
                    <div class="hidden-xs Card-footer-content">
                        <span class="topic-view">
                            <span class="glyphicon glyphicon-eye-open"><span
                                        class="g-font">{{ $topic->views() }} </span></span>
                        </span>

                        <span class="topic-like">
                            <span class="glyphicon glyphicon-heart"><span
                                        class="g-font">{{ $topic->likes() }} </span></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>