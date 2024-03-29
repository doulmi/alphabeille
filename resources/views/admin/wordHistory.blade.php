@extends('admin.index')

@section('content')
<div class="fullscreen">
    <table class="table">
        <thead>
        <tr>
            <th>Id</th>
            <th>Src</th>
            <th>Word</th>
            <th>Explication</th>
        </tr>
        </thead>
        <tbody>
        @foreach($words as $word)
            <tr>
                @if($word->word)
                    <td>{{$word->word->id}}</td>
                    <td>{{$word->src}}</td>
                    <td>{{$word->word->word}}</td>
                    <td>{!! $word->word->explication !!}</td>
                @else
                    {{$word->word->id}} not exist
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="center">
        {!! $words->links() !!}
    </div>
</div>
@endsection


