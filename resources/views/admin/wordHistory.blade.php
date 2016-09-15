@extends('admin.index')

@section('content')
<div class="fullscreen">
    <table class="table">
        <thead>
        <tr>
            <th>Id</th>
            <th>Word</th>
            <th>Explication</th>
        </tr>
        </thead>
        <tbody>
        @foreach($words as $word)
            <tr>
                <td>{{$word->id}}</td>
                <td>{{$word->word->word}}</td>
                <td>{!! $word->word->explication !!}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="center">
        {!! $words->links() !!}
    </div>
</div>
@endsection


