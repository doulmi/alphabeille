<nav class="navbar navbar-default navbar-guest navbar-fixed-top" role="navigation">
    <div class="container-fluid container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}">
                <img class="icon" src="/img/icon.png" alt="Go to home page" style="margin-top: -10px">
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right" id="dropdown_lan">
                <li class="dropdown xs-center">
                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="true"><div class="flag flag-svg-small flag-{{App::getLocale()}}"></div>&nbsp;{{ Config::get('languages')[App::getLocale()] }}&nbsp;
                        <span class="glyphicon glyphicon-chevron-down"></span></a>
                    <ul class="dropdown-menu bullet pull-right" role="menu">
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
                <li class="hide_collapse">
                    <a type="button" href='{{ url("/login") }}' class="shake-horizontal">{{trans('labels.login')}}</a>
                </li>
                <li class="hide_collapse">
                    <a type="button" href='{{ url("/register") }}' class="shake-vertical">{{trans('labels.register')}}</a >
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>