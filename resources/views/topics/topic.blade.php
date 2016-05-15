@extends('app')

@section('title')
    {{$topic->title}}
@endsection

@section('content')
    <div class="body">
        <div class="Header"></div>

        <div class="Banner">
            <div class="Grid-row-flex container">
                <div class="Banner-thumbnail">
                    <img src="{{$topic->avatar}}"
                         alt="$topic->title">
                </div>

                <div class="utility-flex">
                    <h1 class="Banner-heading"> {{$topic->title}} </h1>

                    <div class="Banner-message">
                        <p>{{$topic->description}}</p>

                        <ul class="Banner-bullets">
                            <li>
                                <strong>{{$topic->lessonCount()}}</strong>
                                @lang('labels.lessons')
                            </li>

                            <li>
                                <strong>131</strong>
                                minutes
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="Heading-Fancy row">
            <span class="Heading-Fancy-subtitle">
                @lang('labels.learnSlagon')
            </span>
            <span class='title'>{{ $topic->title }}</span>
        </h2>

        @include('lessons.lessonsList')
    </div>
@endsection


