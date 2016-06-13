
@extends('base')

@section('othercss')
@endsection

@section('text')
    @include('navbar')

    @yield('content')

    @include('footer')
@endsection
