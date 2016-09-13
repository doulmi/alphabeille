@extends('app')

@section('title')@lang('labels.myNotes')@endsection

@section('content')
    <div class="">
        <div class="Header"></div>
        <div class="Header"></div>
        <div class="Card-Collection">
            <div class="row">
                <div class="col-md-12">
                    @if(count($notes) == 0)
                        <div> @lang('labels.noNoteYet') </div>
                    @else
                        <table class="table table-striped note-list">
                            <tr>
                                <th class="no">#</th>
                                <th class="explication">@lang('labels.note')</th>
                                <th>@lang('labels.readable_src')</th>
                                {{--                                    <th>@lang('labels.explication')</th>--}}
                                <th></th>
                            </tr>
                            @foreach($notes as $i => $note)
                                <tr id="row-{{$i}}">
                                    <td>{{$i + 1}}</td>
                                    <td><p id="note-{{$note->id}}" class="contenteditable tooltips-bottom" data-tooltips="@lang('labels.clickToEdit')" contenteditable="true">{!! $note->content !!}</p></td>
                                    <td><a href="{{url($note->url)}}" title="{{$note->title}}">{{$note->title}}</a></td>
                                    <td><a href="javascript:;" class="close tooltips-bottom" data-tooltips="@lang('labels.delete')" onclick="deleteNote('{{$i}}', '{{$note->id}}')">x</a></td>
                                </tr>
                            @endforeach
                        </table>
                        <div class="center">{!! $notes->links() !!}</div>
                        <div class="Header"></div>
                        <div class="Header"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('otherjs')
    <script>
    </script>
@endsection