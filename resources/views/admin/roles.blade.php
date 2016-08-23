@extends('admin.index')

@section('content')
        <button type="button" class="btn btn-info" data-toggle="modal"
                data-target="#myModal">@lang('labels.addRole')</button>

                <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">@lang('labels.addRole')</h4>
                    </div>
                    <form role="form" action="{{url('admin/roles')}}" method="POST">
                        {!! csrf_field() !!}
                        <div class="modal-body" url="">
                            <div class="form-group">
                                <label for="name">@lang('labels.roleName')</label>
                                <input name='name' type="text" class="form-control" >
                            </div>
                            <div class="form-group">
                                <label for="slug">@lang('labels.roleSlug')</label>
                                <input class="name-input form-control" type="text" name="slug" />
                            </div>

                            <div class="form-group">
                                <label for="level">@lang('labels.level')</label>
                                <input class="name-input form-control" type="number" name="level" />
                            </div>

                            <div class="form-group">
                                <label for="description">@lang('labels.roleDesc')</label>
                                <input class="name-input form-control" type="text" name="description" />
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

        <div id="modifyModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">@lang('labels.addRole')</h4>
                    </div>
                    <form role="form" id="modifyForm" action="{{url('admin/roles')}}" method="POST">
                        <input type="hidden" name="_method" value="PUT"/>
                        {!! csrf_field() !!}
                        <div class="modal-body" url="">
                            <div class="form-group">
                                <label for="name">@lang('labels.roleName')</label>
                                <input name='name' type="text" class="form-control" id="roleName">
                            </div>
                            <div class="form-group">
                                <label for="slug">@lang('labels.roleSlug')</label>
                                <input class="name-input form-control" type="text" id="roleSlug" name="slug" />
                            </div>

                            <div class="form-group">
                                <label for="level">@lang('labels.level')</label>
                                <input class="name-input form-control" type="number" id="roleLevel" name="level" />
                            </div>

                            <div class="form-group">
                                <label for="description">@lang('labels.roleDesc')</label>
                                <input class="name-input form-control" type="text" id="roleDescription" name="description" />
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
            {!! $roles->render() !!}
        </div>
        <div class="fullscreen">
            <table class="table ">
                <thead>
                <tr>
                    <th>@lang('labels.roleId')</th>
                    <th>@lang('labels.roleName')</th>
                    <th>@lang('labels.roleSlug')</th>
                    <th>@lang('labels.level')</th>
                    <th>@lang('labels.roleDesc')</th>
                    <th>@lang('labels.actions')</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr id="row-{{$role->id}}">
                            <td scope="row">{{$role->id}}</td>
                            <td>{{$role->name}}</td>
                            <td>{{$role->slug}}</td>
                            <td>{{$role->level}}</td>
                            <td>{{$role->description}}</td>
                            <td><a href="#" class="btn btn-success" data-toggle="modal"
                                   data-target="#modifyModal" onclick="modify({{$role->id}})">@lang('labels.modify')</a></td>
                        </tr>
                @endforeach
                </tbody>
            </table>

        </div>
@endsection

@section('otherjs')
    <script src="/js/adminFullscreen.js"></script>
    <script>

        function modify(id) {
            var tds = $('#row-' + id).children('td');

            var _name = $(tds[1]).html();
            var _slug = $(tds[2]).html();
            var _level = $(tds[3]).html();
            var _description = $(tds[4]).html();

            var modifyForm = $('#modifyForm');
            var name = $('#roleName');
            var slug = $('#roleSlug');
            var level = $('#roleLevel');
            var description = $('#roleDescription');

            modifyForm.attr('action', '{{url('admin/roles')}}' + '/' + id);
            name.val(_name);
            slug.val(_slug);
            level.val(parseInt(_level));
            description.val(_description);
        }
    </script>
@endsection
