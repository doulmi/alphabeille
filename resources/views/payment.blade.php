@extends('base')

@section('title')@lang('labels.paymentProcess')@endsection

@section('text')
    @include('navbar')
    <div class="body grey fullscreen">
        <div class="Header" id="header"></div>
        <div class="lesson-content pay-panel">
            <h2>@lang('labels.paymentProcess')</h2>
            <div class="row">
                <div class="col-md-7 markdown-content">
                    <p>1. @lang('labels.payStep1') </p>
                    <p>2. @lang('labels.payStep2') </p>
                    <p>3. @lang('labels.payStep3', ['price' => $menu->price]) </p>
                    <p>4. @lang('labels.payStep4', ['email' => Auth::user()->email]) </p>
                    <p>5. @lang('labels.payStep5') </p>
                </div>
                <div class="col-md-5">
                    <img src="/img/alipay.png" alt="">
                </div>
            </div>
        </div>
    </div>
@endsection
