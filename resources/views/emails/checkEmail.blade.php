@extends('app')


@extends('base')

{{--<link rel="stylesheet" href="/css/app.css">--}}
@section('text')
    @include('navbar')
    <div class="login fullscreen">
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
        <div class="wrapper">
            <div class="login-container">
                <h3 class="white">{{trans('labels.plsCheckEmail') }}</h3>
                <div>
                    {{trans('labels.checkEmailDesc')}}
                </div>
                <button type="button" class="btn">{{trans('labels.resetPwd')}}</button>
            </div>
        </div>
    </div>
@endsection

@section('otherjs')
    <script src="/js/fullscreen.js"></script>
    <script>
        $(function() {
            var email = $('#email');
        });
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">


                </div>
            </div>
        </div>
    </div>
@endsection

@section('otherjs')
    <script src="/js/fullscreen.js"></script>
@endsection
