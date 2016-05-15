@extends('base')

{{--<link rel="stylesheet" href="/css/app.css">--}}
@section('text')
    @include('navbar')
    <div class="login fullscreen">
        <div class="Header"></div>
        <div class="sky">
            <div class="cloud variant-1"></div>
            <div class="cloud variant-2"></div>
            <div class="cloud variant-3"></div>
            <div class="cloud variant-4"></div>
            <div class="cloud variant-5"></div>
            <div class="cloud variant-6"></div>
            <div class="cloud variant-7"></div>
            <div class="cloud variant-8"></div>
        </div>

        <center>
            <h3 class="white">@lang('labels.404')</h3>
            <img src="/img/404.png" alt="404"/>
        </center>
    </div>
@endsection

@section('otherjs')
    <script src="/js/fullscreen.js"></script>
@endsection


