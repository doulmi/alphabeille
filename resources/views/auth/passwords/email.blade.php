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
                <div class="container">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>

                <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                    {!! csrf_field() !!}
                    <input type="email" name='email' placeholder="{{trans('labels.email')}}" value="{{ old('email') }}">
                    @if ($errors->has('email'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                    @endif
                    <a class="input-group-addon" href="" id="login-addon" data-toggle="tooltip"
                       data-placement="right" title="Tooltip on left"><span :class="eyeIcon"></span></a>
                    <button type="submit" id="login-button">
                        <span class="glyphicon glyphicon-send"></span>
                        {{trans('labels.resetPwd')}}
                    </button>
                </form>
            </div>
        </div>
        @endsection

        @section('otherjs')
            <script src="https://cdn.jsdelivr.net/vue/latest/vue.js"></script>
            <script src="/js/fullscreen.js"></script>

@endsection
