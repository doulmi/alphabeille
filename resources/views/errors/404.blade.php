@extends('base')

@section('text')
    @include('navbar')
    <div class="fullscreen body">
        <div class="Header"></div>

        <div class="container center">

            <img src="/img/404.png" alt="404"/>
            <h2 class="white">@lang('labels.404')</h2>

            <div class="Card-Collection">
                <ul class="tips-list">
                    <li>1. @lang('labels.404tip1', ['url' => '<a href="/">Alphabeille</a>'])</li>
                    <li>2. @lang('labels.404tip2', ['mailto' => '<a href="mailto:alphabeille64@gmail.com" target="_top">Alphabeille</a>'])</li>
                </ul>
            </div>

            <div class="Header"></div>

            <h4 class="white center">@lang('labels.welcomeJoinUs') @lang('labels.copyright')</h4>
        </div>

    </div>
@endsection

@section('otherjs')
    <script src="/js/fullscreen.js"></script>
@endsection


