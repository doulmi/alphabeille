@extends('base')

@section('title')
    {{ trans('titles.info') }}
@endsection

@section('othercss')
@endsection

@section('text')
    @include('navbar')
    <div class="body fullscreen">

        <div class="Header"></div>
        <div class="Header"></div>

        <div class="Card-Collection">
            @if(Session::has('saveSuccess'))
                <div class="alert alert-success">
                    {{trans('labels.' . Session::get('saveSuccess'))}}
                </div>
            @elseif(Session::has('error'))
                <div class="alert alert-danger">
                    {{trans('labels.' . Session::get('error'))}}
                </div>
            @endif

            <div id="validation-errors" class="alert alert-danger" style="display: none;"></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="info-panel">
                        <div class="media">
                            <div class="media-left">
                                <img id='user-avatar2' src="{{$user->avatar}}" alt="64x64"
                                     class="img-circle media-object"
                                     width="64px" height="64px">
                            </div>

                            <div class="media-body">
                                <h3 class="media-heading ">{{$user->name}}</h3>
                                <span>@lang('labels.created_at'){{$user->created_at->diffForHumans()}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="info-sidebar list-group">
                        <a href="#" id="infoBtn" class="list-group-item active"> @lang('labels.accountInfo') </a>
                        <a href="#" id="pwdBtn" class="list-group-item">@lang('labels.accountPwd')</a>
                        {{--<a href="#" id="achieveBtn" class="list-group-item">@lang('labels.achievement')</a>--}}
                        {{--<a href="#" id="punchinBtn" class="list-group-item">@lang('labels.punchin')</a>--}}
                        {{--<a href="#" id="lifetimeBtn" class="list-group-item">@lang('labels.lifetime')</a>--}}
                    </div>
                </div>

                <div class="col-md-8" id="infoTab">
                    <div class="info-panel">
                        <h3>@lang('labels.accountInfo')</h3>

                        <form class="form-horizontal info-group" enctype="multipart/form-data" method="POST"
                              action="{{url( '/uploadAvatar' )}}" id="avatarForm">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label class="col-sm-2 control-label avatar-label">头像</label>
                                <div class="col-sm-10">
                                    <div class="media">
                                        <div class="media-left">
                                            <img id='user-avatar' src="{{$user->avatar}}" alt="64x64"
                                                 class="img-circle media-object"
                                                 width="64px" height="64px">

                                        </div>
                                        <div class="media-body">
                                                <input type="file" name="avatar" id="avatar" class="inputfile"/>
                                                <label for="avatar" id="avatar-btn"
                                                       class="btn">@lang('labels.uplouadAvatar')</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form class="form-horizontal info-group" enctype="multipart/form-data" method="POST"
                              action="{{url( '/users' )}}">
                            {!! csrf_field() !!}
                            <input name="_method" type="hidden" value="PUT">
                            <div class="form-group"><label
                                        class="col-sm-2 control-label">@lang('labels.name')</label>
                                <div class="col-sm-10">
                                    <input class="name-input form-control" type="text" name="name"
                                           value="{{$user->name}}">
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">@lang('labels.qq')</label>
                                <div class="col-sm-10">
                                    <input class="title-input form-control" type="text" name="qq" value="{{$user->QQ}}">
                                </div>
                            </div>

                            <div class="form-group"><label class="col-sm-2 control-label">@lang('labels.wechat')</label>
                                <div class="col-sm-10">
                                    <input class="title-input form-control" type="text" name="wechat"
                                           value="{{$user->wechat}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-2">
                                    <button type="submit" class="btn btn-save">@lang('labels.save')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-8" id="pwdTab" style="display:none">
                    <div class="info-panel">
                        <h3>@lang('labels.accountPwd')</h3>

                        <form class="form-horizontal info-group" method="POST" action="{{url('modifyPwd')}}">
                            {!! csrf_field() !!}
                            <div class="form-group"><label
                                        class="col-sm-2 control-label">@lang('labels.oldPwd')</label>
                                <div class="col-sm-10">
                                    <input class="name-input form-control" type="password" name="oldPwd" id="oldPwd">
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">@lang('labels.newPwd')</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input class="name-input form-control" type="password" id="newPwd" name="newPwd"
                                               value=""/>
                                        <span class="input-group-addon eye" id="eye-btn">
                                            <i id="eye-icon" class="glyphicon glyphicon-eye-close"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-2">
                                    <button type="submit" class="btn btn-save">@lang('labels.modifyPwd')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{--<div id="achieveTab" style="display:none">--}}
                    {{--achieveTab--}}
                {{--</div>--}}
                {{--<div id="punchinTab" style="display:none">--}}
                    {{--punchinTabe--}}
                {{--</div>--}}
                {{--<div id="lifetimeTab" style="display:none">--}}
                    {{--lifetimeTab--}}
                {{--</div>--}}
            </div>
        </div>
    </div>

    <input type="hidden" value="info" id="currentTab"/>
@endsection

@section('otherjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>

    <script>
        $(document).ready(function () {
            var options = {
                beforeSubmit: showRequest,
                success: showResponse,
                dataType: 'json'
            };
            $('#avatar').on('change', function () {
                $('#avatar-btn').html('正在上传...');
                $('#avatarForm').ajaxForm(options).submit();
            });
        });
        function showRequest() {
            return true;
        }

        function showResponse(response) {
            console.log(response);
            if (response.success == false) {
                var responseErrors = response.errors;
                $.each(responseErrors, function (index, value) {
                    if (value.length != 0) {
                        $errors = $("#validation-errors");
                        $errors.append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        $errors.css('display', 'block');
                    }
                });
                $("#validation-errors").show();
            } else {
                $('#avatar-btn').html('上传成功!');
                $('#user-avatar').attr('src', response.avatar);
                $('#user-avatar2').attr('src', response.avatar);
            }
        }

        var eyeIcon = $('#eye-icon');
        var eye = false;

        var eyeBtn = $('#eye-btn');
        var newPwd = $('#newPwd');
        var oldPwd = $('#oldPwd');

        eyeBtn.click(function () {
            eye = !eye;
            if (eye) {
                eyeIcon.removeClass("glyphicon-eye-close").addClass("glyphicon-eye-open");
                newPwd.attr('type', 'text');
                oldPwd.attr('type', 'text');
            } else {
                eyeIcon.removeClass("glyphicon-eye-open").addClass("glyphicon-eye-close");
                newPwd.attr('type', 'password');
                oldPwd.attr('type', 'password');
            }
        });

        var tabs = {
            'info': $("#infoTab"),
            'pwd': $('#pwdTab'),
//            'achieve': $('#achieveTab'),
//            'punchin': $('#punchinTab'),
//            'lifetime': $('#lifetimeTab')
        };

        var buttons = {
            'info': $('#infoBtn'),
            'pwd': $('#pwdBtn'),
//            'achieve': $('#achieveBtn'),
//            'punchin': $('#punchinBtn'),
//            'lifetime': $('#lifetimeBtn')
        };

        var currentTab = $('#currentTab');

//        buttons['lifetime'].click(function () {
//            buttons[currentTab.val()].removeClass('active');
//            buttons['lifetime'].addClass('active');
//
//            tabs[currentTab.val()].fadeOut(function () {
//                currentTab.val('lifetime');
//                tabs['lifetime'].fadeIn(2)
//            });
//        });
//        buttons['punchin'].click(function () {
//            buttons[currentTab.val()].removeClass('active');
//            buttons['punchin'].addClass('active');
//
//            tabs[currentTab.val()].fadeOut(function () {
//                currentTab.val('punchin');
//                tabs['punchin'].fadeIn(2)
//            });
//        });
        buttons['info'].click(function () {
            buttons[currentTab.val()].removeClass('active');
            buttons['info'].addClass('active');

            tabs[currentTab.val()].hide();
            currentTab.val('info');
            tabs['info'].fadeIn(2)
        });

        buttons['pwd'].click(function () {
            buttons[currentTab.val()].removeClass('active');
            buttons['pwd'].addClass('active');

            tabs[currentTab.val()].hide();
            currentTab.val('pwd');
            tabs['pwd'].fadeIn(2)
        });

//        buttons['achieve'].click(function () {
//            buttons[currentTab.val()].removeClass('active');
//            buttons['achieve'].addClass('active');
//
//            tabs[currentTab.val()].fadeOut(function () {
//                currentTab.val('achieve');
//                tabs['achieve'].fadeIn(2)
//            });
//        });

    </script>
@endsection

