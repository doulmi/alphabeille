@extends('base')

@section('title')
    @lang('titles.messageBox')
@endsection

@section('text')
    @include('navbar')
    <div class="body fullscreen">
        <div class="Header" id="header"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-5 full">
                    <ul class="inbox" id="">
                    @if(count($messages) > 0)
                        @foreach($messages as $message)
                        <li class="message">
                                @if($message->isRead)
                                    <div class="" onclick="loadMessage({{$message->id}});">
                                        <span class="title">
                                            <img src="{{$message->from()->avatar}}" class='avatar' alt="">
                                            {{$message->title}}
                                        </span>
                                        <span class="date pull-right">{{$message->created_at->diffForHumans()}}</span><br/>
                                        <span class="content">{{substr($message->content, 0, 50) . '...'}}</span>
                                    </div>
                                @else
                                    <div class="" onclick="loadMessage({{$message->id}})">
                                        <span class="title">
                                            <img src="{{$message->from()->avatar}}" class='avatar' alt="">
                                            {{$message->title}}
                                        </span>
                                        <span class="new-label">@lang('labels.newMsg')</span>
                                        <span class="date pull-right">{{$message->created_at->diffForHumans()}}</span><br/>
                                        <span class="content">{{substr($message->content, 0, 50) . '...'}}</span>
                                    </div>
                                @endif
                        </li>
                        @endforeach
                    @else
                        <li>@lang('labels.emptyMsgbox')</li>
                    @endif
                    </ul>
                </div>

                <div class="col-md-7 full msg-panel" id="msgbox-content">
                    <div id="msg-content-panel" style="display: none;">
                        <div class="msg-contact">
                            @lang('labels.from'):
                            <span id="msg-content-form"></span>
                            <span id="msg-content-date"></span>
                        </div>
                        <div class="msg-header">
                            @lang('labels.title'):
                            <span id="msg-content-title"></span>
                        </div>
                        <div class="msg-body">
                            <span id="msg-content-body"></span>
                        </div>
                    </div>
                    <center>
                        <div id="loader">
                            <img src="/css/svg-loaders/rings.svg" width='100px' alt=""/>
                        </div>
                    </center>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('otherjs')
    <script src="/js/fullscreen.js"></script>
    <script>
        var messageContent = $('#message-content');
        var loader = $('#loader');
        loader.hide();

        var msgContent = $('#msg-content-panel');
        var msgFrom = $('#msg-content-form');
        var msgTitle= $('#msg-content-title');
        var msgDate= $('#msg-content-date');
        var msgBody= $('#msg-content-body');

        var loadMessage = function(id) {
            loader.show();
            msgContent.hide();

            var newLabel = $('.new-label');

            $.get('{{url('api/messages')}}' + '/' + id + '?id=' + '{{Auth::user()->id}}', function(data) {
                console.log(data.data);
                msgFrom.text(data.data.from_name);
                msgTitle.text(data.data.title);
                msgDate.html('<span class="pull-right">' + data.data.created_at + '</span>');
//                msgFrom.html('<img class="avatar" src="' + data.data.avatar + '"/>' + data.data.from_name);
                msgBody.text(data.data.content);

                msgContent.show();
                loader.hide();
            });
        };

        $(function() {
            var headerHeight = $('#header').height();

            var maxHeight = function () {
                var height = $(window).height() - headerHeight;
                $(".full").css('min-height', height * 0.9);
            };

            maxHeight();

            $(window).resize(function () {
                var width = $(window).width();
                if( width >= 990) {
                    maxHeight();
                } else {
                    $(".full").css('min-height', 0 );
                }
            });
        });
    </script>
@endsection