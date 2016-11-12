@extends('admin.index')

@section('content')
    <div class="container">
        <table class="table">
            <thead>
            <tr>
                <th>日期</th>
                <th>分数</th>
                <th>评价</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($infos as $info)
                <tr>
                    <td>{{$info['date']}}</td>
                    <td>{{$info['score']}}</td>
                    <td>{{$info['comment']}}</td>
                    <td>
                        <a href="{{url('admin/90days/students/deleteComment/' . $name . '/' . $info['date'])}}">删除</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

