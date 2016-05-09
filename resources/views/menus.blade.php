@extends('app')

@section('title')
    {{trans('titles.menus')}}
@endsection

@section('content')
    <div class="body">
        <div class="Header"></div>
        <div class="Header"></div>
        <div class="Header"></div>
        @include('menusList')
    </div>
@endsection