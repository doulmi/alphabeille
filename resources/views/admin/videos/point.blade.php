@extends('admin.index')

@section('content')
    <form role="form" action="{{url('admin/videos/' . $video->id . '/points')}}" method="POST">
        {!! csrf_field() !!}
        <div class="row">
            <div class="col-md-7">
                <video id="my_video" class="video-js vjs-default-skin"
                       controls preload data-setup='{ "aspectRatio":"1920:1080" }' data-setup='{"language":"fr"}'>
                    <source src="{{$video->video_url}}" type='video/mp4'>
                </video>

                <center><a class="btn btn-lg btn-success" @click="addCurrentPoint">Cut</a></center>
                <div class="points-panel">
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

                <input type="hidden" name="points" v-bind:value="points">
            </div>
            <div class="col-md-5">
                <div class="video-content grey">
                    <table>
                        <tbody>
                        <tr v-for="line in linesFr">
                            <td class='width40 '><a href='#@{{ $index }}' @click.stop.prevent='seekTo($index)'
                                                    class='seek-btn'
                                                    :class="played.indexOf($index) > -1 > 'active' : ''"></a>
                            </td>
                            <td>
                                <p :class="active == $index ? 'active' : ''">@{{{line}}}</p>
                            </td>
                        </tr>
                        {{--@endforeach--}}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="Header"></div>
            <button type="submit" class="btn btn-lg btn-primary pull-right">提交</button>
        </div>
    </form>

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
                points: [],
                linesFr: [],
                linesZh: []
            },

            ready() {
                var pointStr = '{{$video->points}}';
                this.points = pointStr.split(',');
                this.pointsCount = this.points.length;

                this.linesFr = "{!!$video->parsed_content!!}".split('||');
                this.linesZh = "{!!$video->parsed_content_zh!!}".split('||');
            },

            methods: {
                seekTo(no) {
                    var time = this.points[no];
                    player.currentTime(time);
                },

                addCurrentPoint() {
                    this.points.push(player.currentTime());
                    this.points.sort(function (a, b) {
                        return a - b;
                    });
                },

                deletePoint(no) {
                    this.points.splice(no, 1);
                },

                timeupdate() {
                    var currentTime = player.currentTime();
                    for (var i = 0; i < this.points.length; i++) {
                        if (this.repeatOne) {
                            if (currentTime >= this.points[this.active + 1]) {
                                player.currentTime(this.points[this.active]);
                            }
                        }
                        if (this.active != i && currentTime >= this.points[i]) {
                            this.active = i;
                            this.currentZh = this.linesZh[i];
                            this.currentFr = this.linesFr[i];
                        }

                    }
                }
            }
        });
    </script>
@endsection


