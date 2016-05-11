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
                @if ($errors->has('email'))
                    <div class="errors">{{ trans($errors->first('email'),  ['attribute' => trans('labels.email')]) }}</div>
                @endif
                {{--login--}}
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}" id="reset-form" >
                    {!! csrf_field() !!}
                    <input type="email" required id="email" name='email' placeholder="{{trans('labels.email')}}" value="{{ old('email') }}">

                    <button type="submit" id="reset-button">
                        {{trans('labels.resetPwd')}}
                    </button>

                </form>
            </div>
        </div>
    </div>
@endsection

@section('otherjs')
    <script src="/js/fullscreen.js"></script>
    <script src="/js/validate.js"></script>
    <script>
        $(document).ready(function () {
            var resetBtn = $('#reset-button');
            var resetForm = $('#reset-form');

            resetBtn.prop('disabled', true);
            var email = $('#email');
            email.focusout(function() {
                if( isEmail($(this).val()) ) {
                    resetBtn.removeAttr('disabled');
                } else {
                    resetBtn.prop('disabled', true);
                }
            });

            resetBtn.click(function () {
                if( isEmail($this).val() ) {
                    $(this).html('{{trans('labels.onReset')}}<div class="spinner" id="loader"> <div class="rect1"></div> <div class="rect2"></div> <div class="rect3"></div> <div class="rect4"></div> <div class="rect5"></div> </div>');
                    resetForm.submit();
                    $(this).prop('disabled', true);
                }
            });
        });
    </script>

@endsection
