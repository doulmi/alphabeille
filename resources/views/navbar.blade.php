<nav class="navbar navbar-default navbar-guest navbar-fixed-top" id='navbar' role="navigation">
    <div class="container-fluid container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" id="navbar-toggle" data-toggle="collapse"
                    data-target="#navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand tooltips-bottom shake shake-slow" href="{{ url('/') }}" data-tooltips="@lang('labels.backIndex')">
                <i class="svg-icon svg-logo" id="icon-white "></i>
            </a>
        </div>



        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar-collapse-1">
            <ul class="nav navbar-nav hidden-xs">
                <li class="dropdown ">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                       aria-haspopup="true"
                       aria-expanded="true">
                       @lang('labels.ourLessons')
                    </a>

                    <ul class="dropdown-menu bullet" role="menu">
                        <li><a href="{{url('/free')}}">@lang('labels.freeLessons')</a></li>
                        <li><a href="{{ url('/lessons') }}">@lang('labels.lessons')</a>
                        <li><a href="{{ url('/talkshows') }}">@lang('labels.talkshows')</a>
                        <li><a href="{{ url('/minitalks') }}">@lang('labels.minitalks')</a>
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
                            <span>{{Auth::user()->name}}</span>
                            <i class="glyphicon glyphicon-menu-down"></i>
                        </a>

                        <ul class="dropdown-menu bullet" role="menu">
                            <li>
                                <a href="{{ url('/users/' . Auth::user()->id) }}"><strong>@lang('labels.profile')</strong></a>
                            </li>
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
                    @if(($msgCount =Auth::user()->unreadMessageCount()) > 0)
                        <li class="hidden-xs">
                            <a href="{{url('messages')}}">
                                <i class="svg-icon svg-icon-notification"></i>
                                <span class="white label-svg-notification">{{$msgCount}}</span>
                            </a>
                        </li>
                    @else
                        <li class="hidden-xs">
                            <a href="{{url('messages')}}">
                                <i class="svg-icon svg-icon-notification half-opacity"></i>
                            </a>
                        </li>
                    @endif
                @endif

                @if(Auth::guest())
                    <li class="dropdown flag-btn hidden-xs">
                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="true">
                            <div class="flag flag-svg-small flag-{{App::getLocale()}}"></div>
                            <span class="glyphicon glyphicon-chevron-down"></span></a>
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
                @endif

                @if(Auth::guest())
                    <li class="nav-btn hidden-xs">
                        <a type="button" href='{{ url("/login")}}'
                           class="shake-horizontal">@lang('labels.login')</a>
                    </li>
                    <li class="nav-btn hidden-xs">
                        <a href='{{ url("/register") }}' class="shake-horizontal">@lang('labels.register')</a>
                    </li>
                @endif
                <li class="visible-xs">
                        @if(Auth::guest())
                        <div class="nav-btns center">
                            <a href="{{url('/login')}}" >@lang('labels.login')</a><br/>
                            <a href="{{url('/register')}}" >@lang('labels.register')</a><br/>
                        </div>
                        @else
                        <div class="nav-btns center">
                            <a href="{{ url('/users/' . Auth::user()->id) }}" >
                                <img class="avatar avatar-small" src="{{Auth::user()->avatar}}" alt="avatar">
                                <span>{{Auth::user()->name}}</span>
                            </a><br/>
                        </div>
                        <div class="nav-btns center">
                            <a href="{{url('/users/collect')}}" >
                               @lang('labels.myCollect')
                            </a>
                            <a href="{{url('/logout')}}" >@lang('labels.disconnect')</a>
                        </div>
                        @endif
                    <div class="nav-btns center ">
                        <a href="{{url('/')}}" class="nav-xs-btn">@lang('titles.index')</a>
                        <a href="{{url('/free')}}" class="nav-xs-btn">@lang('labels.free')</a>
                        <a href="{{url('/discussions')}}" class="nav-xs-btn">@lang('titles.discussions')</a>
                    </div>
                    <div class="center nav-btns">
                        @foreach (Config::get('languages') as $lang => $language)
                                <a href="{{ route('lang.switch', $lang) }}" class="nav-xs-btn">
                                    {{--<div class="flag flag-svg-small flag-{{$lang}}"></div>--}}
                                    {{$language}}
                                </a>
                        @endforeach
                    </div>
                </li>

            </ul>
            @if(!Request::is('/'))
            <form action="{{url('search')}}" class="navbar-form navbar-right hidden-md hidden-sm hidden-xs" role="search"
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