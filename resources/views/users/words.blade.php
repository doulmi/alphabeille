@extends('app')

@section('title')
    @lang('labels.myWords')
@endsection

@section('content')
    <div class="">
        <div class="Header"></div>

        <div class="Card-Collection">
            <div class="row">
                <div class="col-md-12">
                    @foreach($wordss as $date => $words)
                        <h2>{{$date}}</h2>
                        <table class="table table-striped word-list" >
                            <tr>
                                <th class="no">#</th>
                                <th class="word">@lang('labels.word')</th>
                                <th class="times">@lang('labels.times')</th>
                                <th class="explication">@lang('labels.explication')</th>
                                <th></th>
                            </tr>
                            @foreach($words as $i => $word)
                                <tr id="word-{{$word->id}}">
                                    <td >{{$i + 1}}</td>
                                    <td class="word-text">{!! $word->word->word !!}</td>
                                    <td >{{$word->times}}</td>
                                    <td >{!! $word->word->explication !!}}</td>
                                    <td><i onclick="removeWord({{$word->id}})" class="glyphicon glyphicon-remove remove-btn"></i></a> </td>
                                </tr>
                            @endforeach
                        </table>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('otherjs')
    <script>
        function removeWord(id) {
            $.post( '{{url("api/wordFav")}}/' + id + '/delete', {'_token' : '{{csrf_token()}}' }, function( data ) {
                $('#word-' + id).remove();
            });
        }
    </script>
@endsection