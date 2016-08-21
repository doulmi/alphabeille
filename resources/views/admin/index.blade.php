<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/bootstrap-markdown.min.css">
    {{--<link rel="stylesheet" href="/css/admin.css">--}}
    @yield('othercss')
    <link rel="stylesheet" href="/css/app.css">
    <style>
        .sidebar {
            position: fixed;
            left: 1%;
            top: 10%;
        }

        .right-slider {
            position: fixed;
            right: 1%;
            top: 10%;
        }
    </style>
    <meta id="token" name="token" value="{{csrf_token()}}">
</head>
<body>

@include('admin.navbar')

<div class="container-fluid">
    <div class="col-md-2 sidebar">
        <div class="list-group">
            <a class="list-group-item category" href="{{url('/')}}">
                <i class="glyphicon glyphicon-home"></i>@lang('titles.index')
            </a>
        </div>
        <div class="list-group">
            <a class="list-group-item category">
                <i class="glyphicon glyphicon-cog"></i>
                @lang('labels.managePermissions')
                <i class="glyphicon glyphicon-menu-right pull-right"></i>
            </a>
            <a href="{{route('adminUsers')}}" class="list-group-item item">
                <i class="glyphicon glyphicon-user"></i>
                @lang('labels.manageUsers')
            </a>
            <a href="{{url('admin/roles')}}" class="list-group-item item">
                <i class="glyphicon glyphicon-lock"></i>
                @lang('labels.manageRoles')
            </a>
            <a href="#" class="list-group-item item">
                <i class="glyphicon glyphicon-wrench"></i>
                @lang('labels.manageOperations')
            </a>
        </div>

        <div class="list-group">
            <a class="list-group-item category">
                <i class="glyphicon glyphicon-cog"></i>
                @lang('labels.manageContent')
                <i class="glyphicon glyphicon-menu-right pull-right"></i>
            </a>
            {{--<a href="{{url('admin/topics')}}" class="list-group-item item">--}}
                {{--<i class="glyphicon glyphicon-list-alt"></i>--}}
                {{--@lang('labels.manageTopics')--}}
            {{--</a>--}}
            {{--<a href="{{url('admin/lessons')}}" class="list-group-item item">--}}
                {{--<i class="glyphicon glyphicon-file"></i>--}}
                {{--@lang('labels.manageLessons')--}}
            {{--</a>--}}
            {{--<a href="{{url('admin/talkshows')}}" class="list-group-item item">--}}
                {{--<i class="glyphicon glyphicon-volume-up"></i>--}}
                {{--@lang('labels.manageTalkshows')--}}
            {{--</a>--}}
            <a href="{{url('admin/minitalks')}}" class="list-group-item item">
                <i class="glyphicon glyphicon-music"></i>
                @lang('labels.manageMinitalks')
            </a>
            <a href="{{url('admin/videos')}}" class="list-group-item item">
                <i class="glyphicon glyphicon-play"></i>
                @lang('labels.manageVideos')
            </a>
            <a href="{{url('admin/tasks')}}" class="list-group-item item">
                <i class="glyphicon glyphicon-edit"></i>
                @lang('labels.videos.state4')
            </a>
            {{--<a href="{{url('admin/videos?desc=1')}}" class="list-group-item item">--}}
                {{--<i class="glyphicon glyphicon-comment"></i>--}}
                {{--@lang('labels.videosNoDesc')--}}
            {{--</a>--}}
            <a href="{{url('admin/comments/lesson')}}" class="list-group-item item">
                <i class="glyphicon glyphicon-music"></i>
                @lang('labels.manageComments')
            </a>
            <a href="{{url('admin/words')}}" class="list-group-item item">
                <i class="glyphicon glyphicon-education"></i>
                @lang('labels.manageWords')
            </a>

        </div>
    </div>

    <div class="col-md-offset-2 col-md-9">
        @yield('content')
    </div>

    <div class="col-md-1 right-slider">
        @yield('actions')
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script src="/js/bootstrap-markdown.js"></script>

@yield('otherjs')
</body>
</html>