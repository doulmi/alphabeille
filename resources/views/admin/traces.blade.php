@extends('admin.index')

@section('content')
    <div class="center">
        {!! $traces->render() !!}
    </div>
    <div class="fullscreen">
        <h2>{{$user->id}} : {{$user->name}}</h2>
        <table class="table">
            <thead>
            <tr>
                <th>id</th>
                <th>readable_type</th>
                <th>readable_id</th>
                <th>date</th>
            </tr>
           </thead>
            <tbody>
                @foreach($traces as $trace)
                    <tr>
                        <td>{{$trace->id}}</td>
                        <td>{{$trace->readable_type}}</td>
                        <td>{{$trace->readable_id}}</td>
                        <td>{{$trace->created_at}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('otherjs')
    <script src="/js/adminFullscreen.js"></script>
@endsection
