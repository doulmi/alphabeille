<nav class="navbar navbar-default ">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}" data-toggle="tooltip" data-placement="bottom" title="@lang('labels.backIndex')"  target="_blank">
                <i class="svg-icon svg-logo" id="icon-white"></i>
            </a>
        </div>


        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
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
                <li><a href="#" class="black">@lang('labels.welcome'): {{Auth::user()->name}} </a></li>
            </ul>

        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>