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
                    {{--<div class="alert alert-danger" role="alert">{{ trans('labels.email') . ' ' . $errors->first('email') }}</div>--}}
                    <div class="error">@lang($errors->first('email'), ['attribute' => trans('labels.email')])</div>
                @endif
                @if ($errors->has('password'))
                        <div class="error">@lang($errors->first('password'), ['attribute' => trans('labels.pwd')])</div>
                    {{--<div class="alert alert-danger"--}}
                         {{--role="alert">{{ trans('labels.pwd') . ' ' . $errors->first('password') }}</div>--}}
                @endif
            </div>
            <form class="form-horizontal" role="form" method="POST" action="{{ url('register') }}" id="register-form"  v-else>
                {!! csrf_field() !!}
                <input type="email" name='email' value="{{ old('email') }}">
                <input type="@{{ pwdType }}" name='password' placeholder="@lang('labels.pwd')">
               <a class="input-group-addon eye" href="" id="login-addon" @click.stop.prevent="iconToggle" ><span :class="eyeIcon"></span></a>
                <button type="submit" id="login-button">@lang('labels.resetPwd')</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('otherjs')
    <script src="https://cdn.jsdelivr.net/vue/latest/vue.js"></script>
    <script src="/js/fullscreen.js"></script>
    <script>
        new Vue({
            el: 'body',

            data: {
                openEye: false
            },

            methods: {
                iconToggle() {
                    this.openEye = !this.openEye;
                }
            },

            computed: {
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

