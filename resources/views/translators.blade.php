@extends('app')

@section('title')@lang('labels.translator.title')@endsection

@section('header')
    <meta name="description" content="@lang('labels.translator.description')">
    <meta name="Keywords" content="@lang('labels.translator.keywords')">
@endsection

@section('content')
    <div class="translator-wall">
        <div class="jumbotron ">
            <div class="container">
                <div class="col-md-6 col-md-offset-3 ad">
                    <h2>Alphabeille翻译社群</h2>
                    Alphabeille翻译社群提供一个平台，让对翻译有热枕的同好能够琢磨翻译技巧，同时让更多使用者能够精进法语能力！
                    <div><a class="btn btn-ads" href="mailto:alphabeilleStudio@gmail.com">@lang('labels.joinus')</a></div>
                </div>
            </div>
        </div>
        <div class="Card-Collection">
            @foreach($translators as $i => $translator)
                @if($i % 4 == 0)
                    <div class="row">
                @endif
                        <div class="col-md-3">
                            <div class="media">
                                <div class="media-left">
                                    <a href="#">
                                        <img src="{{$translator->avatar}}" alt="{{$translator->name . '\'s avatar'}}"
                                             class="img-circle media-object" width="64px" height="64px">
                                    </a>
                                </div>

                                <div class="media-body">
                                    <h4 class="media-heading">
                                        {{$translator->name}}
                                    </h4>
                                    @lang('labels.translateVideos', ['num' => $translator->translatedNumber()])
                                </div>
                            </div>
                        </div>

                @if($i % 4 == 3)
                    </div>
                @endif
            @endforeach

            <div class="Header"></div>
            <div class="Header"></div>
        </div>
    </div>
@endsection
