@extends('app')

@section('title')
    @lang('labels.menus.title')
@endsection

@section('header')
    <meta name="description" content="@lang('labels.menus.description')">
    <meta name="Keywords" content="@lang('labels.menus.keywords')">
@endsection

@section('content')
    @if(Session::has('payFailed'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            {{ trans('labels.' . Session::get('payFailed'))}}
        </div>
    @endif
    <div class="menu-bg">
        @include('menusList')
        <div class="margin-top"></div>
        <div class="Header"></div>
    </div>
@endsection

{{--@section('otherjs')--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.min.js"></script>--}}
    {{--<script>--}}
        {{--$('img.Card-image').lazyload();--}}
    {{--</script>--}}
{{--@endsection--}}