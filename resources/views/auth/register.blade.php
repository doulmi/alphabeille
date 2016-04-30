@extends('base')

{{--<link rel="stylesheet" href="/css/app.css">--}}
@section('text')
    @include('navbar')
    <div class="login fullscreen">
        {{--<div class="rainbow-preloader" style="position:absolute">--}}
            {{--<div class="rainbow-stripe"></div>--}}
            {{--<div class="rainbow-stripe"></div>--}}
            {{--<div class="rainbow-stripe"></div>--}}
            {{--<div class="rainbow-stripe"></div>--}}
            {{--<div class="rainbow-stripe"></div>--}}
            {{--<div class="shadow"></div>--}}
            {{--<div class="shadow"></div>--}}
        {{--</div>--}}

        <div class="sky" style="float:left">
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
                <div class="container">
                    @if ($errors->has('email'))
                        <div class="alert alert-danger"
                             role="alert">{{ trans('labels.email') . ' ' . $errors->first('email') }}</div>
                    @endif
                    @if ($errors->has('password'))
                        <div class="alert alert-danger"
                             role="alert">{{ trans('labels.pwd') . ' ' . $errors->first('password') }}</div>
                    @endif
                </div>
                {{--login--}}
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}" v-if="isLogin">
                    {!! csrf_field() !!}
                    <input type="email" name='email' placeholder="{{trans('labels.email')}}" value="{{ old('email') }}">
                    <input type="@{{ pwdType }}" name='password' placeholder="{{trans('labels.pwd')}}">
                    <a class="input-group-addon" id="login-addon" @click="iconToggle" data-toggle="tooltip"
                    data-placement="right" title="Tooltip on left"><span :class="eyeIcon"></span></a>
                    <button type="submit" id="login-button">
                        {{trans('labels.login')}}
                    </button>

                    <div class="form-group">
                        <div class="center">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember"> {{trans('labels.rememberme') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-link" href="{{ url('/password/reset') }}">{{trans('labels.forgetPwd')}}</a>
                </form>

                {{--register--}}
                <form class="form-horizontal" role="form" method="POST" action="{{ url('register') }}" v-else>
                    {!! csrf_field() !!}
                    <input type="email" name='email' placeholder="{{trans('labels.email')}}" value="{{ old('email') }}">
                    <input type="@{{ pwdType }}" name='password' placeholder="{{trans('labels.pwd')}}">
                    <a class="input-group-addon" id="login-addon" @click="iconToggle" data-toggle="tooltip"
                    data-placement="right" title="Tooltip on left"><span :class="eyeIcon"></span></a>
                    <button type="submit" id="login-button">{{trans('labels.register')}}</button>
                </form>
            </div>

            <input type="hidden" id="login" v-model="isLogin" value="{{ Request::is('login') }}"/>
        </div>
        @endsection

        @section('otherjs')
            <script src="https://cdn.jsdelivr.net/vue/latest/vue.js"></script>
            <script>
                $(document).ready(function () {
                    fullscreenSupport();
                });

                $(window).resize(function () {
                    fullscreenSupport();
                    resizeFont();
                    navbar.toggleConnectBtn();
                });

                var fullscreenSupport = function () {
                    var height = $(window).height();
                    var width = $(window).width();
                    $(".fullscreen").css('min-height', height);
                };

                new Vue({
                    el: 'body',

                    data: {
                        openEye: true,
                        isLogin : true
                    },

                    methods: {
                        iconToggle() {
                            this.openEye = !this.openEye;
                        }
                    },

                    computed: {
                        login() {
                            return this.isLogin ? 'login' : 'register';
                        },
                        eyeIcon() {
                            return this.openEye ? 'glyphicon glyphicon-eye-open' : 'glyphicon glyphicon-eye-close';
                        },

                        pwdType() {
                            return this.openEye ? 'text' : 'password';
                        }
                    }
                });
            </script>
@endsection
