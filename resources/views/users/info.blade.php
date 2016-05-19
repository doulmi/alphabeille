@extends('app')

@section('title')
    {{ trans('titles.info') }}
@endsection

@section('content')
    <div class="body fullscreen">

        <div class="Header"> </div>
        <div class="Header"> </div>

        <h2 class="Heading-Fancy row">
            <span class='title'>{{$user->name}}</span>
        </h2>

        <div class="Card-Collection">
            <span>@lang('labels.join_at') {{$user->created_at->diffForHumans()}}</span>
        </div>

        <div class="header-container"></div>
    </div>
@endsection
