@extends('admin.index')

@section('content')
    @foreach($words as $word)
        {{$word}}  <br/>
    @endforeach
@endsection


