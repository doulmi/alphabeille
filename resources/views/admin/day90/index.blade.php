@extends('admin.index')

@section('content')
    <style>
        .filter-list {
            display: block;
            max-height: 500px;
            top: 60px;
            overflow: auto;
            left: 15px;
        }
    </style>

    <div class="form-group">
        <label for="today">Name</label>
        <input class="form-control" v-model="today" id="today"/>
    </div>
    <div class="form-group">

        <label for="studentList">Name</label>
        <input list="student" id="studentList" class="form-control" v-on:keyup="filter" v-model="student"
               v-on:click="filter">
        <ul class="dropdown-menu filter-list" v-if="showList">
            <li v-for="stu in filterStudents"><a href="#"
                                                 v-on:click="select(stu.name)">@{{ stu.name + ' - ' + stu.nickname }}</a>
            </li>
        </ul>
    </div>

    <div class="form-group">
        <label for="score">@lang('labels.score'):</label> @{{ score }}<br/>

        <div class="btn-group" role="group" aria-label="Score group">
            <button type="button" class="btn btn-default" v-for="sc in scores"
                    v-on:click="setScore(sc)">@{{ sc }}</button>
        </div>
    </div>

    <div class="form-group">
        <label for="content">@lang('labels.content')</label>

        <textarea class="name-input form-control" rows="10" id="content" data-provide="markdown" name="content"
                  v-model="comment"></textarea>
    </div>

    <a href="#" class="btn btn-primary pull-right" @click.stop.prevent="onSubmit">提交</a>
    <a href="{{url('admin/90days/daily')}}" class="btn btn-success" @click.stop.prevent="onGenerate">生成今日报告</a>
    <a href="{{url('admin/90days/weekly')}}" class="btn btn-danger" @click.stop.prevent="onGenerate">生成本周报告</a>
@endsection

@section("otherjs")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>
    <script src="{{asset('js/toastr.min.js')}}"></script>
    <script>
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        var vm = new Vue({
            el: 'body',
            ready: function () {
                var that = this;
                var day = new Date();
                var dd = day.getDate();
                dd = dd < 10? '0' + dd : dd;
                var mm = day.getMonth() + 1;
                mm = mm < 10 ? '0' + mm : mm;
                var yyyy = day.getFullYear();

                this.today = "" + yyyy + mm + dd;

                $.ajax({
                    type: "get",
                    url: '{{url('admin/api/90days/students')}}',
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                    success: function (response) {
                        that.students = response;
                    }
                });
            },
            data: {
                students: [],
                student: '',
                isLoading: true,
                filterStudents: [],
                score: 3,
                today: '',
                scores: [1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5],
                comment: ''
            },

            computed: {
                showList: function () {
                    return this.filterStudents.length > 0 && this.isLoading;
                }
            },

            methods: {
                setScore: function (score) {
                    this.score = score;
                },

                filter: function (e) {
                    if (e.keyCode == 13) {
                        this.select(this.filterStudents[0].name);
                    } else {
                        var student = this.student.toLowerCase();
                        this.isLoading = true;
                        if (this.student.trim() != '') {
                            this.filterStudents = this.students.filter(function (stu) {
                                return stu.name.toLowerCase().indexOf(student) > -1 || stu.nickname.toLowerCase().indexOf(student) > -1;
                            });
                        } else {
                            this.filterStudents = this.students;
                        }
                    }
                },

                select: function (name) {
                    this.student = name;
                    this.isLoading = false;
                },

                onSubmit: function () {
                    if (this.student.trim() === '' || this.comment.trim() === '') {
                        toastr.error("@lang('labels.addFailed')");
                        return;
                    }
                    var that = this;
                    $.ajax({
                        type: "post",
                        url: '{{url('admin/90days/students')}}',
                        data: {name: this.student, comment: this.comment, score: this.score, today: this.today},
                        headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                        success: function () {
                            toastr.success("@lang('labels.addSuccess')");
                            that.student = '';
                            that.comment = '';
                            that.score = 3;
                        }
                    });
                }
            }
        });


        $(function () {
            $('body').on('click', function (e) {
                if (e.target.id != 'studentList') {
                    vm.isLoading = false;
                }
            })
        })
    </script>
@endsection