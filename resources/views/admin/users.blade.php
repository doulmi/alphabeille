@extends('admin.index')

@section('content')
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

<form id="userForm" action="{{url('admin/users')}}" method="GET">
    {!! csrf_field() !!}

        <div class="input-group">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                        id="searchFieldType"
                        aria-haspopup="true" width="60px" aria-expanded="false">@lang('labels.email') <span
                            class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#"
                           onclick="changeSearchField('email', '@lang('labels.email')')">@lang('labels.email')</a>
                    </li>
                    <li><a href="#"
                           onclick="changeSearchField('role', '@lang('labels.role')')">@lang('labels.role')</a>
                    </li>
                    <li><a href="#"
                           onclick="changeSearchField('wechat', '@lang('labels.wechat')')">@lang('labels.wechat')</a>
                    </li>
                    <li><a href="#"
                           onclick="changeSearchField('name', '@lang('labels.name')')">@lang('labels.name')</a>
                    </li>
                    <li><a href="#"
                           onclick="changeSearchField('qq', '@lang('labels.qq')')">@lang('labels.qq')</a>
                    </li>
                </ul>
            </div><!-- /btn-group -->
            <input type="text" value="{{Request::has('search') ? Request::get('search') : ''}}"
                   class="form-control" id="searchValue" placeholder="Search for...">
      <span class="input-group-btn">
        <button class="btn btn-default" onclick="goSearch()" type="button">Go!</button>
      </span>
        </div><!-- /input-group -->

    <div class="Header"></div>
    <div>
        @if($loginUser->can('user.create'))
            <button type="button" class="btn btn-info" data-toggle="modal"
                    data-target="#myModal">@lang('labels.addUser')</button>
        @endif
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
        <table class="table">
            <thead>
            <tr>
                <?php
                $urls = [];
                $cols = ['id', 'avatar', 'role', 'email', 'name', 'wechat', 'qq', 'created_at', 'last_login_at'];
                ?>
                @foreach($cols as $colName)
                    <th>
                        <a href="#" onclick="order('{{$colName}}')">@lang('labels.' . $colName) <i
                                    class="glyphicon glyphicon-triangle-bottom"></i></a>
                    </th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                @if($user->level() <= $loginUser->level())
                    <tr>
                        <th scope="row">{{$user->id}}</th>
                        <td><img src="{{$user->avatar}}" width="60px" height="60px" alt=""></td>
                        <td>
                            @if($loginUser->can('user.role'))
                                    <!-- Split button -->
                            <div class="btn-group">
                                <button type="button" class="btn btn-default" id="roleBtn-{{$user->id}}">
                                    {{ ($role = $user->getRoles()->sortByDesc('level')->first()) ? $role['name'] : 'Member' }}
                                </button>
                                <button type="button" class="btn btn-default dropdown-toggle"
                                        data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    @foreach($roles as $role)
                                        <li><a href="#"
                                               onclick="changeRole('{{$user->id}}', '{{$role->id}}', '{{$role->name}}' )">{{$role->name}}</a>
                                        </li>
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
                        <th>{{$user->last_login_at->diffForHumans()}}</th>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
        <input type="hidden" name="orderBy"
               value="{{Request::has('orderBy') ? Request::get('orderBy') : 'created_at'}}" id="orderBy">
        <input type="hidden" name="dir" value="{{Request::has('dir') ? Request::get('dir') : 'ASC'}}" id="dir">
        <input type="hidden" name="searchField"
               value="{{Request::has('searchField') ? Request::get('searchField') : 'email'}}" id="searchField">
        <input type="hidden" name="search" value="{{Request::has('search') ? Request::get('search') : ''}}"
               id="search">
    </div>
</form>
@endsection

@section('otherjs')
    <script src="/js/adminFullscreen.js"></script>
    <script>
        var userForm = $('#userForm');
        var orderBy = $('#orderBy');
        var dir = $('#dir');
        var searchField = $('#searchField');
        var search = $('#search');

        function order(colName) {
            if (orderBy.val() == colName) {
                if (dir.val() == 'ASC') {
                    dir.val('DESC');
                } else {
                    dir.val('ASC');
                }
            } else {
                dir.val('ASC');
                orderBy.val(colName);
            }
            console.log(colName, orderBy.val(), dir.val());
            userForm.submit();
        }

        var searchFieldType = $('#searchFieldType');
        function changeSearchField(field, name) {
            searchFieldType.html(name + '<span class="caret"></span>');
            searchField.val(field);
        }

        var searchValue = $('#searchValue');
        function goSearch() {
            search.val(searchValue.val());
            console.log(search.val(), searchField.val());
            userForm.submit();
        }
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


        var changeRole = function (userId, roleId, roleName) {
            $('#roleBtn-' + userId).text(roleName);
            $.get('{{ 'users/changeRole/'}}' + userId + '/' + roleId, function (response) {
                console.log(response.data['message']);
            });
        }
    </script>
@endsection
