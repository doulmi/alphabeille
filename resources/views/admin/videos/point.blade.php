@extends('admin.index')

@section('othercss')
    <link rel="stylesheet" href="/css/bootstrap-tagsinput.css">
@endsection

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

                <div class="Header"></div>
                <button type="submit" class="btn btn-lg btn-primary pull-right">提交</button>
            </div>
        </div>
        <input type="hidden" name="points" v-bind:value="points">

    </form>
@endsection

@section('otherjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>
    <script src="/js/bootstrap-tagsinput.min.js"></script>
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

            @if($edit)
            ready() {
                this.$http.get('{{url("admin/api/videos/" .$video->id . "/points")}}', function(response) {
                    var times = response.split(',');
                    for(var i = 0; i < times.length; i ++) {
                        this.points.push(parseFloat(times[i]));
                    }
                    console.log(response);
                }.bind(this));
            },
            @endif

            methods: {
                addCurrentPoint() {
                    this.points.push(player.currentTime());
                    this.points.sort(function (a, b) {
                        return a - b;
                    });
                },

                seekTo(no) {
                    var time = this.points[no];
                    console.log(time);
                    player.currentTime(time);
                },

                deletePoint(no) {
                    this.points.splice(no, 1);
                }
            }
        });
    </script>
@endsection


