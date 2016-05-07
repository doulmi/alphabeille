
@extends('base')

@section('othercss')
    {{--<link rel="stylesheet" type="text/css" href="/css/waves.css"/>--}}
@endsection

@section('text')
    @if(Auth::guest())
        @include('navbar')
    @else
        @include('navbar_login')
    @endif

    @yield('content')

    @include('footer')
@endsection

