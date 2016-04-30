@extends('app')

@section('title')
    {{ trans('titles.index') }}
@endsection

@section('othercss')
@endsection

@section('content')

    <div class="header">
    </div>

    <div class="continer-fluid body">
        <center>
            <div class="container topics">
                <div class="row">
                    <h3 class="white col-xs-2 pull-left">{{ trans('labels.topics') }}</h3>
                    <h3 class="white col-xs-2 pull-right">{{ trans('labels.more') }}</h3>
                </div>
                <div class="row ">
                    <div class="col-md-3 col-xs-6 topic">
                        <img src="/img/break.jpg" alt="">
                    </div>

                    <div class="col-md-3 col-xs-6 topic">
                        <img src="/img/break.jpg" alt="">
                    </div>

                    <div class="col-md-3 col-xs-6 topic">
                        <img src="/img/break.jpg" alt="">
                    </div>

                    <div class="col-md-3 col-xs-6 topic">
                        <img src="/img/break.jpg" alt="">
                    </div>
                </div>
            </div>

            <div class="container topics">
                <div class="row">
                    <h3 class="white col-xs-2 pull-left">{{ trans('labels.talkshows') }}</h3>
                    <h3 class="white col-xs-2 pull-right">{{ trans('labels.more') }}</h3>
                </div>

                <div class="row">
                    <div class="col-md-3 col-xs-6 topic">
                        <img src="/img/break.jpg" alt="">
                    </div>

                    <div class="col-md-3 col-xs-6 topic">
                        <img src="/img/break.jpg" alt="">
                    </div>

                    <div class="col-md-3 col-xs-6 topic">
                        <img src="/img/break.jpg" alt="">
                    </div>

                    <div class="col-md-3 col-xs-6 topic">
                        <img src="/img/break.jpg" alt="">
                    </div>
                </div>
            </div>


        <div class="bitch">
            <ul id="">
                <li class="layer umbra" data-depth="0.20"><img src="/img/umbra.png"></li>
                <li class="layer cloud1" data-depth="0.50"><img src="/img/cloud1.png"></li>
                <li class="layer cloud2" data-depth="1.00"><img src="/img/cloud2.png"></li>
            </ul>


            <div class="container Classes">
                <div class="row">
                    <div class="Class Economy col-md-2 col-md-offset-3-5">
                        <h3 style="margin-top: 0px;">Economy<br>
                            <span>Great for small websites<br> and blogs</span>
                        </h3>
                        <div class="White">
                            <div class="Dots"></div>
                            <h4 class="text-center"><i class="fa fa-eur"></i>1.<span>77/mo</span></h4>
                            <ul>
                                <li>5GB Mthly Bandwidth</li>
                            </ul>
                        </div>
                        <div class="Gray">
                            <a class="Buy" href="/hosting/new-hosting-account?hostingType=economy">Buy </a>
                        </div>
                    </div>

                    <div class="Class Premium col-md-2 col-md-offset-1">
                        <h3 style="margin-top: 0px;">Premium<br>
                            <span>More email addresses,<br>more storage</span>
                        </h3>
                        <div class="White">
                            <div class="Dots"></div>
                            <h4 class="text-center">
                                <i class="fa fa-eur"></i>2.<span>65/mo</span>
                            </h4>
                            <ul>
                                <li>10GB Mthly Bandwidth</li>
                            </ul>
                        </div>
                        <div class="Gray" >
                            <a class="Buy" href="/hosting/new-hosting-account?hostingType=premium">Buy </a>
                        </div>
                    </div>

                    <div class="Class Business col-md-2 col-md-offset-1">
                        <h3 style="margin-top: 0px;">Business<br>
                            <span>Perfect for<br>high-quality content</span>
                        </h3>
                        <div class="White">
                            <div class="Dots"></div>
                            <h4 class="text-center">
                                <i class="fa fa-eur"></i>7.<span>09/mo</span>
                            </h4>
                            <ul>
                                <li>20GB Mthly Bandwidth</li>
                            </ul>
                        </div>
                        <div class="Gray">
                            <a class="Buy" href="/hosting/new-hosting-account?hostingType=business">Buy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </center>
    </div>

@endsection

@section('otherjs')
    <script src="/js/jquery.parallax.js"></script>

    <script>
        var $scene = $('#scene').parallax();
        $scene.parallax('enable');
    </script>
@endsection
