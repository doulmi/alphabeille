@extends('admin.index')

@section('content')
@if($loginUser->can('create.user'))
<button type="button" class="btn btn-info" data-toggle="modal"
        data-target="#myModal">@lang('labels.addUser')</button>
@endif

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">@lang('labels.addUser')</h4>
            </div>
            <form role="form" action="{{url('admin/users')}}" method="POST">
                <input name="_method" type="hidden" value="PUT">
                {!! csrf_field() !!}
                <div class="modal-body" url="">
                    <div class="form-group">
                        <label for="email">@lang('labels.email')</label>
                        <input name='email' type="email" class="form-control" id="email">
                    </div>
                    <div class="form-group">
                        <label for="email">@lang('labels.pwd')</label>
                        <div class="input-group">
                            <input class="name-input form-control" type="password" id="pwd" name="password"
                                   value=""/>
                    <span class="input-group-addon eye" id="eye-btn">
                        <i id="eye-icon" class="glyphicon glyphicon-eye-close"></i>
                    </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-default">@lang('labels.submit')</button>
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">@lang('labels.close')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(Session::has('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        {{trans('labels.' . Session::get('success'))}}
    </div>
@endif
@if ($errors->has('email'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        @lang($errors->first('email'), ['attribute' => trans('labels.email')] )
    </div>
@endif
@if ($errors->has('password'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        @lang($errors->first('password'), ['attribute' => trans('labels.pwd')] )
    </div>
@endif

<div class="center">
    {!! $users->render() !!}
</div>
<div class="fullscreen">
    <table class="table ">
        <thead>
        <tr>
            <th>@lang('labels.userId')</th>
            <th>@lang('labels.level')</th>
            <th>@lang('labels.email')</th>
            <th>@lang('labels.username')</th>
            <th>@lang('labels.wechat')</th>
            <th>@lang('labels.qq')</th>
            <th>@lang('labels.created_at')</th>
        </tr>
        </thead>
        <tbody>

        @foreach($users as $user)
            @if($user->level() <= $loginUser->level())
                <tr>
                    <th scope="row">{{$user->id}}</th>
                    <td>
                        @if($loginUser->can('edit.userRole'))
                                <!-- Split button -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-default" id="roleBtn-{{$user->id}}">
                                {{ ($role = $user->getRoles()->sortByDesc('level')->first()) ? $role['name'] : 'Member' }}
                            </button>
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                @foreach($roles as $role)
                                <li><a href="#" onclick="changeRole('{{$user->id}}', '{{$role->id}}', '{{$role->name}}' )">{{$role->name}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        @else
                        {{ ($role = $user->getRoles()->sortByDesc('level')->first()) ? $role['name'] : 'Member' }}
                        @endif
                    </td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->wechat}}</td>
                    <td>{{$user->qq}}</td>
                    <td>{{$user->created_at->diffForHumans()}}</td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>

</div>
@endsection

@section('otherjs')
    <script>
        var eyeIcon = $('#eye-icon');
        var eye = false;

        var eyeBtn = $('#eye-btn');
        var pwd = $('#pwd');

        eyeBtn.click(function () {
            eye = !eye;
            if (eye) {
                eyeIcon.removeClass("glyphicon-eye-close").addClass("glyphicon-eye-open");
                pwd.attr('type', 'text');
            } else {
                eyeIcon.removeClass("glyphicon-eye-open").addClass("glyphicon-eye-close");
                pwd.attr('type', 'password');
            }
        });

        $(window).resize(function () {
            fullscreenSupport();
        });

        var fullscreenSupport = function () {
            var height = $(window).height();
            $(".fullscreen").css('max-height', height - 250);
        };

        $(function () {
            fullscreenSupport();
        });

        var changeRole = function(userId, roleId, roleName) {
            $('#roleBtn-' + userId).text(roleName);
            $.get('{{ 'users/changeRole/'}}' + userId + '/' + roleId, function(response) {
                console.log(response.data['message']);
            });
        }
    </script>
@endsection
