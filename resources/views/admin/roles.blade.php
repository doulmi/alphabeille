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
                        <input name="_method" type="hidden" value="PUT">
                        {!! csrf_field() !!}
                        <div class="modal-body" url="">
                            <div class="form-group">
                                <label for="name">@lang('labels.roleName')</label>
                                <input name='name' type="text" class="form-control" id="name">
                            </div>
                            <div class="form-group">
                                <label for="slug">@lang('labels.roleSlug')</label>
                                <input class="name-input form-control" type="text" id="slug" name="slug" />
                            </div>

                            <div class="form-group">
                                <label for="level">@lang('labels.level')</label>
                                <input class="name-input form-control" type="number" id="level" name="level" />
                            </div>

                            <div class="form-group">
                                <label for="description">@lang('labels.roleDesc')</label>
                                <input class="name-input form-control" type="text" id="description" name="description" />
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
                </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <th scope="row">{{$role->id}}</th>
                            <td> {{$role->name}} </td>
                            <td> {{$role->slug}} </td>
                            <td> {{$role->level}} </td>
                            <td> {{$role->description}} </td>
                        </tr>
                @endforeach
                </tbody>
            </table>

        </div>
@endsection

@section('otherjs')

    <script src="/js/adminFullscreen.js"></script>
    {{--<script>--}}
        {{--$(window).resize(function () {--}}
            {{--fullscreenSupport();--}}
        {{--});--}}

        {{--var fullscreenSupport = function () {--}}
            {{--var height = $(window).height();--}}
            {{--$(".fullscreen").css('max-height', height - 250);--}}
        {{--};--}}

        {{--$(function () {--}}
            {{--fullscreenSupport();--}}
        {{--});--}}

    {{--</script>--}}
@endsection
