
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
                <li><img class='avatar avatar-small' src="{{Auth::user()->avatar}}"></li>

                <li class="dropdown xs-center">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="true"><strong>{{Auth::user()->name}}</strong><span class="glyphicon glyphicon-chevron-down"
                                                                                         style="margin-left:5px;"
                                                                                         aria-hidden="true"></span></a>
                    <ul class="dropdown-menu bullet pull-right" role="menu">
                        <li><a href="{{ url('/profile') }}"><strong>{{ trans('labels.profile') }}</strong></a></li>
                        <li><a href="{{ url('/logout') }}"><strong>{{ trans('labels.disconnect') }}</strong></a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>


