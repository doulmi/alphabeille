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
                    @if(count($wordss) == 0)
                        <div class="fullscreen">
                            No word searched
                        </div>
                    @else
                        @foreach($wordss as $date => $words)
                            <h2>{{$date}}</h2>
                            <table class="table table-striped word-list">
                                <tr>
                                    <th class="no">#</th>
                                    <th class="word">@lang('labels.word')</th>
                                    <th class="times">@lang('labels.times')</th>
                                    <th class="explication">@lang('labels.explication')</th>
                                    <th></th>
                                </tr>
                                @foreach($words as $i => $word)
                                    <tr id="word-{{$word->id}}">
                                        <td>{{$i + 1}}</td>
                                        <td class="word-text">{!! $word->word !!}</td>
                                        <td>{{$word->times}}</td>
                                        <td id="td-{{$i}}">
                                            @if(strlen($word->explication) > 400)
                                                {!! substr($word->explication, 0, 400) !!}
                                                <br/>
                                                <button class="btn-link" onclick='more("{{$i}}", "{{str_replace("\r\n", '', $word->explication)}}")'>@lang('labels.more')</button>
                                            @else
                                                {!! $word->explication !!}
                                            @endif
                                        </td>
                                        <td><i onclick="removeWord({{$word->id}})"
                                               class="glyphicon glyphicon-remove remove-btn"></i></a> </td>
                                    </tr>
                                @endforeach
                            </table>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('otherjs')
    <script>
        function removeWord(id) {
            $.post('{{url("api/wordFav")}}/' + id + '/delete', {'_token': '{{csrf_token()}}'}, function (data) {
                $('#word-' + id).remove();
            });
        }

        function more(id, explication) {
            console.log(explication);
            $('#td-' + id).html(explication);
        }
    </script>
@endsection