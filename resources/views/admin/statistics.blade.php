@extends('admin.index')

@section('content')
    <div class="container ">
        <h2>视频数据</h2>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 statistics blue">
                <div class="details">
                    <div class="number">
                        <span >{{$todayVideoViews . '/' . $videoViews}}</span>
                    </div>
                    <div class="desc">今日点击/所有点击</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 statistics red">
                <div class="details">
                    <div class="number">
                        <span >{{$todayTranslateNum . '/' . $translateNum}}</span>
                    </div>
                    <div class="desc">今日翻译/所有翻译</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 statistics green">
                <div class="details">
                    <div class="number">
                        <span >{{$todayCheckFrNum . '/'. $checkFrNum}}</span>
                    </div>
                    <div class="desc">今日法语校对/所有法语校对</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 statistics purple">
                <div class="details">
                    <div class="number">
                        <span >{{ $todayValidNum . '/' . $validNum }}</span>
                    </div>
                    <div class="desc">今日翻译确认/所有翻译确认</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 statistics yellow">
                <div class="details">
                    <div class="number">
                        <span>{{$todayNewVideosNum . '/' . $videosNum}}</span>
                    </div>
                    <div class="desc">今日新视频/所有视频</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 statistics soft">
                <div class="details">
                    <div class="number">
                        <span >{{ $translatedVideoNum / $publishedVideoNum}}</span>
                    </div>
                    <div class="desc">已翻译/已发布视频</div>
                </div>
            </div>
        </div>

        <h2>用户数据</h2>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 statistics blue">
                <div class="details">
                    <div class="number">
                        <span>{{$todayUserNum. '/' . $userNum}}</span>
                    </div>
                    <div class="desc">新用户/所有用户</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 statistics red">
                <div class="details">
                    <div class="number">
                        <span >{{ $todayVipNum . '/' . $vipNum}}</span>
                    </div>
                    <div class="desc">新VIP/所有VIP</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 statistics green">
                <div class="details">
                    <div class="number">
                        <span >{{$translatorsNum}}</span>
                    </div>
                    <div class="desc">翻译实习生</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('otherjs')
    <script></script>
@endsection
