@extends('base')

@section('content')
@endsection
<div id='chatroom'>
    <ul>
        <li v-for="msg in msgs">@{{ msg }}</li>
    </ul>

    <input type="text" v-model="msg"/>
    <button type="button" @onclic='sendMsg' class="btn btn-primary">Send</button>
</div>
@section('otherjs')
    <script src="https://cdn.jsdelivr.net/vue/latest/vue.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.0/vue-resource.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.4.6/socket.io.min.js"></script>
    <script>
        var socket = io("localhost:3000");

        var vm = new Vue({
            el : '#chatroom',
            data : {
                inputMsg : '',
                msgs : []
            },

            ready : function() {
                socket.on('test-channel:NewMessage', function(data) {
                    this.msgs.push(data.name);
                }.bind(this));
            },

            methods : {
                sendMsg () {

                }
            }
        })
    </script>
@endsection
