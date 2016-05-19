<nav class="navbar navbar-default navbar-guest navbar-fixed-top" id='navbar' role="navigation">
    <div class="containerfluid container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" id="navbar-toggle" data-toggle="collapse"
                    data-target="#navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}" data-toggle="tooltip" data-placement="bottom" title="@lang('labels.backIndex')">
                <img class="icon" src="/img/icon.png" alt="Go to home page">
            </a>
        </div>

        <form class="navbar-form navbar-left hidden-md hidden-sm hidden-xs" role="search">
            <div class="form-group">
                <div class="input-group navbar-search">
                    <input type="text" class="navbar-search-input form-control"
                           aria-label="Amount (to the nearest dollar)" placeholder="@lang('labels.whatuwant')">
                    <span class="input-group-addon navbar-search-btn"><span class="glyphicon glyphicon-search "></span></span>
                </div>
            </div>
        </form>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right" id="menu-xs">
                @if(!Auth::guest())
                <li>
                    <a href="{{url('messages')}}">
                        <i class="glyphicon glyphicon-envelope message-icon">
                            <span class="badge msg-badge">{{$msgCount =Auth::user()->unreadMessageCount()}}</span>
                        </i>
                    </a>

                    {{--<ul class="dropdown-menu dropdown-msg bullet pull-right inbox" role="menu">--}}
                        {{--@if($msgCount > 0)--}}
                            {{--@foreach(Auth::user()->messages() as $message)--}}
                                {{--<li class="message">--}}
                                    {{--@if($message->isRead)--}}
                                        {{--<div class="" onclick="loadMessage({{$message->id}});">--}}
                                        {{--<span class="title">--}}
                                            {{--<img src="{{$message->from()->avatar}}" class='avatar' alt="">--}}
                                            {{--{{$message->title}}--}}
                                        {{--</span>--}}
                                            {{--<span class="date pull-right">{{$message->created_at->diffForHumans()}}</span><br/>--}}
                                            {{--<span class="content">{{substr($message->content, 0, 50) . '...'}}</span>--}}
                                        {{--</div>--}}
                                    {{--@else--}}
                                        {{--<div class="" onclick="loadMessage({{$message->id}})">--}}
                                        {{--<span class="title">--}}
                                            {{--<img src="{{$message->from()->avatar}}" class='avatar' alt="">--}}
                                            {{--{{$message->title}}--}}
                                        {{--</span>--}}
                                            {{--<span class="new-label">@lang('labels.newMsg')</span>--}}
                                            {{--<span class="date pull-right">{{$message->created_at->diffForHumans()}}</span><br/>--}}
                                            {{--<span class="content">{{substr($message->content, 0, 50) . '...'}}</span>--}}
                                        {{--</div>--}}
                                    {{--@endif--}}
                                {{--</li>--}}
                            {{--@endforeach--}}
                        {{--@else--}}
                            {{--<li>@lang('labels.emptyMsgbox')</li>--}}
                        {{--@endif--}}
                        {{--@if($msgCount > 0)--}}
                            {{--@foreach(Auth::user()->messages() as $message)--}}
                                {{--<li>--}}
                                    {{--<a href="{{url('message' . $message->id)}}">--}}
                                        {{--@if($message->isRead)--}}
                                        {{--<div class="message read">--}}
                                            {{--<span class="title">{{$message->title}}</span><br/>--}}
                                            {{--<span class="content">{{$message->content}}</span>--}}
                                            {{--<span class="date pull-right">{{$message->created_at->diffForHumans()}}</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<div class="message notread">--}}
                                            {{--<span class="title">{{$message->title}}</span><br/>--}}
                                            {{--<span class="content">{{$message->content}}</span>--}}
                                            {{--<span class="date pull-right">{{$message->created_at->diffForHumans()}}</span>--}}
                                        {{--</div>--}}
                                        {{--@endif--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                            {{--@endforeach--}}
                            {{--<li><a href="{{url('messages')}}" class="btn btn-default">@lang('labels.more')</a></li>--}}
                        {{--@else--}}
                            {{--<li>@lang('labels.emptyMsgbox')</li>--}}
                        {{--@endif--}}
                    {{--</ul>--}}
                </li>

                <li class="dropdown profile-btn xs-center">
                    <a href="#" class="top15 dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="true">
                        {{--<span class="label label-limit" > {{Auth::user()->download}}/{{Auth::user()->downloadMax}} </span>--}}
                        <img class='avatar avatar-small' src="{{Auth::user()->avatar}}"/>
                        <span>{{Auth::user()->name}}</span>
                    </a>

                    <ul class="dropdown-menu bullet pull-right" role="menu">
                        <li><a href="{{ url('/users/' . Auth::user()->id) }}"><i class="glyphicon glyphicon-user"></i><strong>@lang('labels.profile')</strong></a></li>
                        <li><a href="{{ url('/logout') }}"><span class="glyphicon glyphicon-log-out"></span><strong>@lang('labels.disconnect')</strong></a></li>
                    </ul>
                </li>
                <li data-toggle="tooltip" data-placement="bottom" title="@lang('labels.series')"><i class="glyphicon glyphicon-fire series-label"></i><span class="orange right-clip">{{Auth::user()->download}}</span></li>

                <li data-toggle="tooltip" data-placement="bottom" title="@lang('labels.downloadLimit')"><i class="glyphicon glyphicon-paperclip limit-label green"></i><span class="green">{{Auth::user()->download}}</span></li>
                @endif


                <li class="dropdown flag-btn hidden-xs">
                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="true">
                        <div class="flag flag-svg-small flag-{{App::getLocale()}}"></div>
                        <span class="glyphicon glyphicon-chevron-down"></span></a>
                    <ul class="dropdown-menu bullet pull-right"  role="menu">
                        @foreach (Config::get('languages') as $lang => $language)
                            @if ($lang != App::getLocale())
                                <li>
                                    <a href="{{ route('lang.switch', $lang) }}">
                                        <div class="flag flag-svg-small flag-{{$lang}}"></div>
                                        <strong>{{$language}}</strong></a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>


                <li class="visible-xs">
                    <div>
                            <ul>
                                <center>
                                @foreach (Config::get('languages') as $lang => $language)
                                <li class="list-lans"><a href="{{ route('lang.switch', $lang) }}">
                                        <div class="flag flag-svg-small flag-{{$lang}}"></div>
                                    </a>
                                </li>
                                @endforeach
                                </center>
                            </ul>
                    </div>
                </li>

                @if(Auth::guest())
                    <li class="nav-btn">
                        <a type="button" href='{{ url("/login") }}'
                           class="shake-horizontal">@lang('labels.login')</a>
                    </li>
                    <li class="nav-btn">
                        <a href='{{ url("/register") }}' class="shake-vertical">@lang('labels.register')</a>
                    </li>
                @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->

</nav>