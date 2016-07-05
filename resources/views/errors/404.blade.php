@extends('base')

@section('text')
    @include('navbar')
    <div class="fullscreen body">
        <div class="Header"></div>

        <div class="Card-Collection center">
            <img src="/img/404.png" alt="404"/>
            <h2 class="c404">@lang('labels.404')</h2>
        </div>
    </div>
@endsection

