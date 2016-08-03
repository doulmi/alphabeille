@extends('base')

@section('title')
    @lang('labels.login.title')
@endsection

@section('header')
    <meta name="description" content="@lang('labels.login.description')">
    <meta name="Keywords" content="@lang('labels.login.keywords')">
    <meta name="baidu-site-verification" content="4Y6Akg4Bz5" />
@endsection

@section('text')
    @include('navbar')
    <div class="login fullscreen">
        <div class="wrapper">
            <div class="login-container">
                @if ($errors->has('email'))
                    <div class="errors" >@lang($errors->first('email'), ['attribute' => trans('labels.email')] )</div>
                @endif
                @if ($errors->has('password'))
                    <div class="errors">@lang($errors->first('password'), ['attribute' => trans('labels.pwd')] )</div>
                @endif
                {{--login--}}
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}" id="login-form"
                      v-if="isLogin">
                    {!! csrf_field() !!}
                    <h2 class="white">@lang('labels.loginLabel')</h2>
                    <input type="email" id='login-email' autofocus name='email' placeholder="@lang('labels.email')" value="{{ old('email') }}">
                    <input type="@{{ pwdType }}" id='login-pwd' name='password' placeholder="@lang('labels.pwd')">
                    <a class="input-group-addon eye" id="login-addon" href="" @click.stop.prevent="iconToggle"
                       data-toggle="tooltip"
                       data-placement="right" title="@lang('labels.togglePwd')"><span :class="eyeIcon"></span></a>
                    <button type="submit" id="login-button">
                        @lang('labels.login')
                    </button>

                    <br/>
                    <br/>
                    <a class="link link-left" href="{{ url('/password/reset') }}">@lang('labels.forgetPwd')</a>
                    <a class="link link-right" id="toRegister" @click.stop.prevent="toRegister"
                       href="">@lang('labels.noCount')</a>
                    <input type="hidden" value="{{Request::get('redirect_url')}}" name="redirect_url"/>
                </form>

                {{--register--}}
                <form class="form-horizontal" role="form" method="POST" action="{{ url('register') }}"
                      id="register-form" v-else>
                    {!! csrf_field() !!}
                    <h2 class="white">@lang('labels.register')</h2>
                    <input type="email" id="reg-email" autofocus name='email' placeholder="@lang('labels.email')" value="{{ old('email') }}">
                    <input type="@{{ pwdType }}"  data-toggle="tooltip"
                           data-placement="right" id='reg-pwd' name='password' placeholder="@lang('labels.pwd')">
                    <a class="input-group-addon eye" href="" id="login-addon" @click.stop.prevent="iconToggle"
                       data-toggle="tooltip"
                       data-placement="right" title="@lang('labels.togglePwd')"><span :class="eyeIcon"></span></a>
                    <button type="submit" id="register-button">@lang('labels.register')</button>
                    <br/>
                    <br/>
                    <input type="hidden" value="{{Request::get('redirect_url')}}" name="redirect_url"/>
                    <a class="link" id="toLogin" @click.stop.prevent="toLogin" href="">@lang('labels.hasCount')</a>
                </form>
            </div>

            <input type="hidden" id="login" v-model="isLogin" value="{{ Request::is('login') }}"/>
        </div>
    </div>
@endsection

@section('otherjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>
    <script src="/js/validate.js"></script>
    <script>
        $(document).ready(function () {
            $('.errors').fadeOut(10 * 1000, function(){
                fullscreenSupport();
            });

            var registerBtn = $('#register-button');
            var loginBtn = $('#login-button');
            var loginForm = $('#login-form');
            var registerForm = $('#register-form');

            function validBtn(email, pwd, btn) {
                if( isEmail(email) && isLengthMoreThan(pwd, 6)) {
                    btn.removeAttr('disabled');
                } else {
                    btn.attr('disabled','disabled');
                }
            }

            registerBtn.click(function () {
                $(this).html('<div class="spinner" id="loader"> <div class="rect1"></div> <div class="rect2"></div> <div class="rect3"></div> <div class="rect4"></div> <div class="rect5"></div> </div>');
                registerForm.submit();
                $(this).prop('disabled', true);
            });

            loginBtn.click(function () {
                $(this).html('<div class="spinner" id="loader"> <div class="rect1"></div> <div class="rect2"></div> <div class="rect3"></div> <div class="rect4"></div> <div class="rect5"></div> </div>');
                loginForm.submit();
                $(this).prop('disabled', true);
            });
        });

        new Vue({
            el: 'body',

            data: {
                openEye: false,
                isLogin: true
            },

            created: function () {
                window.addEventListener('keydown', this.next)
            },

            methods: {
                iconToggle : function() {
                    this.openEye = !this.openEye;
                },
                toRegister : function() {
                    this.isLogin = false;
                    console.log(this.isLogin);
                },
                toLogin: function() {
                    this.isLogin = true;
                    console.log(this.isLogin);
                },
                next : function(e) {
                    var enterCode = 13;
                    if(e.which == enterCode) {
                        if(this.isLogin) {
                            $('#login-form').submit();
                        } else {
                            $('#register-form').submit();
                        }
                    }
                }
            },

            computed: {
                login : function() {
                    return this.isLogin ? 'login' : 'register';
                },
                eyeIcon : function() {
                    return this.openEye ? 'glyphicon glyphicon-eye-open' : 'glyphicon glyphicon-eye-close';
                },

                pwdType: function() {
                    return this.openEye ? 'text' : 'password';
                }
            }
        });
    </script>
@endsection
