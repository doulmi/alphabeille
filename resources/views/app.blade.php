
@extends('base')

@section('othercss')
    {{--<link rel="stylesheet" type="text/css" href="/css/waves.css"/>--}}
@endsection

@section('text')
    @include('navbar')

    @yield('content')

    @include('footer')
@endsection

