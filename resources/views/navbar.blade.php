<nav class="navbar navbar-default navbar-guest navbar-fixed-top" id='navbar' role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" id="navbar-toggle" data-toggle="collapse"
                    data-target="#navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand tooltips-bottom " href="{{ url('/') }}" data-tooltips="@lang('labels.backIndex')">
                <i class="svg-icon svg-logo shake shake-slow" id="icon-white">
                </i>
            </a>
            <a class="navbar-brand tooltips-bottom " href="{{ url('/') }}" data-tooltips="@lang('labels.backIndex')">
                <img class="logo-text" src="/img/logo-text.png" alt="Alphabeille蜂言法语">
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar-collapse-1">
            <ul class="nav navbar-nav center-nav" id="menu-xs">
                <li class="dropdown profile-btn hidden-xs">
                    <a href="#" class="top15 dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="true">
                        <span>@lang('labels.channels')</span>
                        <i class="glyphicon glyphicon-menu-down"></i>
                    </a>

                    <ul class="dropdown-menu bullet" role="menu">
                        <li><a href="{{ url('/videos') }}">@lang('labels.videos')</a>
                        <li><a href="{{ url('/minitalks') }}">@lang('labels.minitalks')</a>
                    </ul>

                </li>
                <li class="dropdown profile-btn hidden-xs">
                    <a href="#" class="top15 dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="true">
                        <span>@lang('labels.levels')</span>
                        <i class="glyphicon glyphicon-menu-down"></i>
                    </a>
                    <ul class="dropdown-menu bullet" role="menu">
                        <li><a href="{{ url('/videos/level/beginner') }}">@lang('labels.beginner')</a>
                        <li><a href="{{ url('/videos/level/intermediate') }}">@lang('labels.intermediate')</a>
                        <li><a href="{{ url('/videos/level/advanced') }}">@lang('labels.advanced')</a>
                    </ul>
                </li>
                <li class="dropdown profile-btn hidden-xs">
                    <a href="#" class="top15 dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="true">
                        <span>@lang('labels.ourCourses')</span>
                        <i class="glyphicon glyphicon-menu-down"></i>
                    </a>
                    <ul class="dropdown-menu bullet" role="menu">
                        <li><a href="{{ url('/basicCourses') }}">@lang('labels.basicCourses')</a>
                        <li><a href="{{ url('/oralFormation') }}">@lang('labels.oralFormation')</a>
                        <li><a href="{{ url('/privateCourses') }}">@lang('labels.privateCourses')</a>
                    </ul>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right " id="menu-xs">
                @if(!Auth::guest())
                    <li class="dropdown profile-btn hidden-xs">
                        <a href="#" class="top15 dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-haspopup="true"
                           aria-expanded="true">
                            <img class='avatar avatar-small' src="{{Auth::user()->avatar}}"/>
                            <span @if(Auth::user()->level() > 1)class="vip"@endif>{{Auth::user()->name}}</span>
                            <i class="glyphicon glyphicon-menu-down"></i>
                        </a>

                        <ul class="dropdown-menu bullet" role="menu">
                            <li>
                                <a href="{{ url('/users/' . Auth::user()->id) }}"><strong>@lang('labels.profile')</strong></a>
                            </li>
                            <li><a href="{{ url('/users/words') }}"><strong>@lang('labels.myWords')</strong></a>
                            <li><a href="{{ url('/users/collect') }}"><strong>@lang('labels.myCollect')</strong></a>
                            <li><a href="{{ url('/logout') }}"><strong>@lang('labels.disconnect')</strong></a>
                            </li>
                        </ul>
                    </li>

                    <li data-tooltips="@lang('labels.series', ['days' => Auth::user()->series])"
                        class="li-miel tooltips-bottom hidden-xs">
                        <i class="svg-icon svg-icon-miel"></i>
                        <span class="white label-svg-miel" id="punchin">{{Auth::user()->series}}</span>
                    </li>
                    {{--@if(($msgCount =Auth::user()->unreadMessageCount()) > 0)--}}
                        {{--<li class="hidden-xs">--}}
                            {{--<a href="{{url('messages')}}">--}}
                                {{--<i class="svg-icon svg-icon-notification"></i>--}}
                                {{--<span class="white label-svg-notification">{{$msgCount}}</span>--}}
                            {{--</a>--}}
                        {{--</li>--}}
                    {{--@else--}}
                        {{--<li class="hidden-xs">--}}
                            {{--<a href="{{url('messages')}}">--}}
                                {{--<i class="svg-icon svg-icon-notification half-opacity"></i>--}}
                            {{--</a>--}}
                        {{--</li>--}}
                    {{--@endif--}}
                @endif

                {{--                @if(Auth::guest())--}}
                <li class="dropdown flag-btn hidden-xs">
                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="true">
                        <div class="flag flag-svg-tiny flag-{{App::getLocale()}}"></div>
                        {{--<span class="glyphicon glyphicon-chevron-down"></span></a>--}}
                        <ul class="dropdown-menu bullet" role="menu">
                            @foreach (Config::get('languages') as $lang => $language)
                                @if ($lang != App::getLocale())
                                    <li>
                                        <a href="{{ route('lang.switch', $lang) }}">
                                            <div class="flag flag-svg-micro flag-{{$lang}}"></div>
                                            <strong>{{$language}}</strong></a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                </li>
                {{--@endif--}}

                @if(Auth::guest())
                    <li class="nav-btn hidden-xs">
                        <a type="button" href='{{ url("/login")}}'>@lang('labels.login')</a>
                    </li>
                    <li class="nav-btn hidden-xs">
                        <a href='{{ url("/register") }}'>@lang('labels.register')</a>
                    </li>
                @endif
                <li class="visible-xs"> <a href="{{url('/')}}" class="nav-xs-btn">@lang('labels.index')</a> </li>
                <li class="visible-xs"> <a href="{{url('/videos')}}" class="nav-xs-btn">@lang('labels.videos')</a> </li>
                <li class="visible-xs"> <a href="{{url('/minitalks')}}" class="nav-xs-btn">@lang('labels.minitalks')</a> </li>
{{--                <li class="visible-xs"> <a href="{{url('/lessons')}}" class="nav-xs-btn">@lang('labels.lessons')</a> </li>--}}
                @if(Auth::guest())
                    <li class="visible-xs">
                        <a href="{{url('/login')}}">@lang('labels.login')</a>
                    </li>
                    <li class="visible-xs">
                        <a href="{{url('/register')}}">@lang('labels.register')</a>
                    </li>
                @else
                    {{--<li class="visible-xs">--}}
                        {{--<a href="{{ url('/users/' . Auth::user()->id) }}">--}}
                            {{--<img class="avatar avatar-small" src="{{Auth::user()->avatar}}" alt="avatar">--}}
                            {{--<span>{{Auth::user()->name}}</span>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    <li class="visible-xs">
                        <a href="{{url('/users/collect')}}">
                            @lang('labels.myCollect')
                        </a>
                    </li>
                    <li class="visible-xs">
                        <a href="{{url('/logout')}}">@lang('labels.disconnect')</a>
                    </li>
                @endif

                <li class="visible-xs">
                    @foreach (Config::get('languages') as $lang => $language)
                        <a href="{{ route('lang.switch', $lang) }}" class="nav-xs-btn">
                            {{--<div class="flag flag-svg-small flag-{{$lang}}"></div>--}}
                            {{$language}}
                        </a>
                    @endforeach
                </li>
            </ul>
            @if(!Request::is('/'))
                <form action="{{url('search')}}" class="navbar-form navbar-right hidden-md hidden-sm hidden-xs"
                      role="search"
                      id="searchForm" method="get">
                    <div class="form-group">
                        <div class="input-group navbar-search">
                            <input type="text" class="navbar-search-input form-control" name="keys" id="keys"
                                   aria-label="" placeholder="@lang('labels.whatuwant')"
                                   value="{{Request::get('keys') }}">
                    <span onclick="search()" class="input-group-addon navbar-search-btn"><span
                                class="glyphicon glyphicon-search "></span></span>
                        </div>
                    </div>
                </form>
            @endif
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->

</nav>