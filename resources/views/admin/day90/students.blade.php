@extends('admin.index')

@section('content')
    <div class="container">
        <table class="table">
            <thead>
            <tr>
                <th>名</th>
                <th>昵称</th>
                <th>签到次数</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($names as $name)
                <tr>
                    <td>{{$name['name']}}</td>
                    <td>{{$name['nickname']}}</td>
                    <td>{{count($name['info'])}}</td>
                    <td>
                        <a href="{{url('admin/90days/students/' . $name['name'])}}">查看详情</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
