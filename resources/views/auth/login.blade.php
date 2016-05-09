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
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}" id="login-form" v-if="isLogin">
                    {!! csrf_field() !!}
                    <input type="email" name='email' placeholder="{{trans('labels.email')}}" value="{{ old('email') }}">
                    <input type="@{{ pwdType }}" name='password' placeholder="{{trans('labels.pwd')}}">
                    <a class="input-group-addon eye" id="login-addon"  href="" @click.stop="iconToggle" data-toggle="tooltip"
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
                    <a class="link link-left" href="{{ url('/password/reset') }}">{{trans('labels.forgetPwd')}}</a>
                    <a class="link link-right" id="toRegister" @click.stop.prevent="toRegister" href="">{{ trans('labels.noCount') }}</a>
                </form>

                {{--register--}}
                <form class="form-horizontal" role="form" method="POST" action="{{ url('register') }}" id="register-form"  v-else>
                    {!! csrf_field() !!}
                    <input type="email" name='email' placeholder="{{trans('labels.email')}}" value="{{ old('email') }}">
                    <input type="@{{ pwdType }}" name='password' placeholder="{{trans('labels.pwd')}}">
                    <a class="input-group-addon eye" href="" id="login-addon" @click.stop="iconToggle" data-toggle="tooltip"
                    data-placement="right" title="Tooltip on left"><span :class="eyeIcon"></span></a>
                    <button type="submit" id="login-button">{{trans('labels.register')}}</button>
                    <br/>
                    <br/>
                    <a class="link" id="toLogin" @click.stop.prevent="toLogin" href="" >{{trans('labels.hasCount')}}</a>
                </form>
            </div>

            <input type="hidden" id="login" v-model="isLogin" value="{{ Request::is('login') }}"/>
        </div>
        @endsection

        @section('otherjs')
            <script src="https://cdn.jsdelivr.net/vue/latest/vue.js"></script>
            <script src="/js/fullscreen.js"></script>
            <script>
//                $(document).ready(function() {
//                    var eyeBtn = $('.eye');
//                    eyeBtn.bind('click', function() {
//
//                    });
//                    var toRegisterBtn = $('#toRegister');
//                    var toLoginBtn = $('#toLogin');
//                    var registerForm = $('#register-form');
//                    var loginForm = $('#login-form');
//
//                });
            </script>
            <script>
                new Vue({
                    el: 'body',

                    data: {
                        openEye: true,
                        isLogin : true
                    },

                    methods: {
                        iconToggle() {
                            this.openEye = !this.openEye;
                        },
                        toRegister() {
                            this.isLogin = false;
                            console.log(this.isLogin);
                        },
                        toLogin() {
                            this.isLogin = true;
                            console.log(this.isLogin);
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
