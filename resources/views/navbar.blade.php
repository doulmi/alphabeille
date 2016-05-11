<nav class="navbar navbar-default navbar-guest navbar-fixed-top" id='navbar' role="navigation">
    <div class="containerfluid container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}">
                <img class="icon" src="/img/icon.png" alt="Go to home page">
            </a>
        </div>

        <form class="navbar-form navbar-left hidden-md hidden-sm hidden-xs" role="search">
            <div class="form-group">
                <div class="input-group navbar-search">
                    <input type="text" class="navbar-search-input form-control"
                           aria-label="Amount (to the nearest dollar)" placeholder="{{trans('labels.whatuwant')}}">
                    <span class="input-group-addon navbar-search-btn"><span class="glyphicon glyphicon-search "></span></span>
                </div>
            </div>
        </form>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right" id="menu-xs">
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
                           class="shake-horizontal">{{trans('labels.login')}}</a>
                    </li>
                    <li class="nav-btn">
                        <a href='{{ url("/register") }}' class="shake-vertical">{{trans('labels.register')}}</a>
                    </li>
                @else
                    <li><img class='avatar avatar-small' src="{{Auth::user()->avatar}}"></li>

                    <li class="dropdown xs-center">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="true"><strong>{{Auth::user()->name}}</strong><span
                                    class="glyphicon glyphicon-chevron-down"
                                    style="margin-left:5px;"
                                    aria-hidden="true"></span></a>
                        <ul class="dropdown-menu bullet pull-right" role="menu">
                            <li><a href="{{ url('/profile') }}"><strong>{{ trans('labels.profile') }}</strong></a></li>
                            <li><a href="{{ url('/logout') }}"><strong>{{ trans('labels.disconnect') }}</strong></a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>