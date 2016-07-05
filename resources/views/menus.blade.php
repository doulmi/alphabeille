@extends('app')

@section('title')
    @lang('labels.menus.title')
@endsection

@section('header')
    <meta name="description" content="@lang('labels.menus.description')">
    <meta name="Keywords" content="@lang('labels.menus.keywords')">
@endsection

@section('content')
    <div class="body">
        <div class="Header"></div>
        <div class="Header"></div>
        <div class="Header"></div>
        @include('menusList')
    </div>
@endsection