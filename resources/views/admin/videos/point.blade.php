@extends('admin.index')

@section('content')
    <form role="form" action="{{url('admin/videos/' . $video->id . '/points')}}" method="POST">
        {!! csrf_field() !!}
        <div class="row">
            <div class="col-md-5">
                <video id="my_video" class="video-js vjs-default-skin"
                       controls preload data-setup='{ "aspectRatio":"1920:1080" }' data-setup='{"language":"fr"}'>
                    <source src="{{$video->video_url}}" type='video/mp4'>
                </video>


                <div class="points-panel">
                    <center><a class="btn btn-lg btn-success" @click="addCurrentPoint">Cut</a></center>
                    {{--<input type="text" id="points" v-bind:value="points" data-role="tagsinput">--}}
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Time</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="point in points">
                            <th scope="row">@{{ $index }}</th>
                            <td>@{{ point }}</td>
                            <td><a href="#" @click="deletePoint($index)">Delete</a></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="col-md-7">
                <div class="video-content-panel">
                    {!! $video->parsed_content !!}
                </div>
            </div>
        </div>
        @endsection

        @section('otherjs')
            <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.0/vue-resource.min.js"></script>
            <script src="http://vjs.zencdn.net/5.10.7/video.js"></script>

            <script>
                var player;
                videojs("my_video").ready(function () {
                    player = this;
                    player.play();
                    $('#my_video').show();
                });

                Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
                new Vue({
                    el: 'body',
                    data: {
                        points: []
                    },

                    ready() {
                        var pointStr = '{{$video->points}}';
                        this.points = pointStr.split(',');
                        console.log(this.points);
                    },

                    methods: {
                        seekTo(no) {
                            var time = this.points[no];
                            console.log(time);
                            player.currentTime(time);
                        }
                    }
                });
            </script>
@endsection


