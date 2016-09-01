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
                    <h2>Alphabeille翻译组</h2>
                    @lang('labels.translator.description')
                    <div><a class="btn btn-ads" href="mailto:alphabeilleStudio@gmail.com">@lang('labels.joinus')</a></div>
                </div>
            </div>
        </div>
        <div class="container">
            <?php $i = 0; ?>
            @foreach($translators as  $translator)
                @if($i % 4 == 0)
                    <div class="row">
                @endif
                        <div class="col-md-3">
                            <div class="media">
                                <div class="media-left">
                                    <a >
                                        <img src="{{$translator->avatar}}" alt="{{$translator->name . '\'s avatar'}}"
                                             class="img-circle media-object" >
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
                <?php $i ++ ?>
            @endforeach

            <div class="Header"></div>
            <div class="Header"></div>
        </div>
    </div>
@endsection
