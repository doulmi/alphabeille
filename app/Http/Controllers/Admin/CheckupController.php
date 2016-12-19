<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CheckupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.day90.index');
    }

    public function studentIndex() {
        $prefix = '90days:students:';
        $students = Redis::keys($prefix . '*');
        $len = strlen($prefix);
        $names = array_map(function ($student) use ($len, $prefix) {
            $wechatName = substr($student, $len);
            $nickName = Redis::get($prefix . $wechatName);
            $scores = Redis::keys('*:' . $wechatName . ':score');
            $infos = [];
            foreach($scores as $score) {
                $date = substr($score, 0, 8);
                $infos[] = [
                    'date' => $date,
                    'score' => Redis::get($score),
                    'comment' => Redis::get($date . ':' . $wechatName . ':comment')
                ];
            }
            array_sort($infos, function($info, $info2) {
              return $info < $info2;
            });

            return [
                'name' => $wechatName,
                'nickname' => $nickName,
                'info' => $infos
            ];
        }, $students);
        return view('admin.day90.students', compact('names'));
    }

    public function studentShow($name) {
        $scores = Redis::keys('*:' . $name . ':score');
        $infos = [];
        foreach($scores as $score) {
            $date = substr($score, 0, 8);
            $infos[] = [
                'date' => $date,
                'score' => Redis::get($score),
                'comment' => Redis::get($date . ':' . $name. ':comment')
            ];
        }

        return view('admin.day90.show', compact('infos', 'name'));
    }

    public function deleteComment($name, $date) {
        Redis::del($date . ':' . $name . ':score');
        Redis::del($date . ':' . $name . ':comment');
        return redirect('admin/90days/students/'. $name);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = [
            'alice坤' => ['alice北京', 2, 2.5],
            'Anaïs' => ['Anaïs', 0, 0],
            'Box noir' => ['新疆+教师+Alpha', 3, 3],
            '涔瑶粥' => ['涔瑶粥', 4, 3.5],
            '春天 希思黎' => ['春天 希思黎', 2, 2],
            'chen珊珊' => ['广州-christine-老师', 5, 4.5],
            'cry琴' => ['cry琴', 3, 2.5],
            '邓珂琳' => ['格勒-学生-kelin', 4, 0],
            '豆豆圆子' => ['南京 学生 圆子', 4, 3.5],
            '.' => ['武汉 .', 2, 0],
            '独家记忆' =>  ['Paris 小青', 3, 3],
            '格子' => ['非洲-格子', 4, 0],
            '孩儿他妈' => ['天津-孩儿他妈', 2, 2.5],
            '黑小妖的白日梦' => ['巴黎-化学-薇', 0, 3],
            'Hellen' => ['中国-Hellen', 2, 2],
            '互联网+教育' => ['南昌-幼师-互联网+教育', 2, 3],
            'irislo' => ['广州 助理 iris', 3, 2.5],
            '君' =>  ['石家庄-学生-君', 2, 2.5],
            'Juniper' => ['长沙 学生 竹子', 2, 2.5],
            '空白格' => ['昆明-学生-空白格', 3, 3],
            '柳醒龙Ludovic' => ['珠海-规划狗-柳醒龙Ludovic', 3, 3],
            'Life fashion paris' => ['巴黎-售货员-emma', 3, 3],
            '每天被自己帅醒' => ['biubiu-雷恩-学生', 3, 3],
            'Nikki' => ['广州-Nikki-英语老师', 4, 4],
            '千叶' => ['中国重庆-法语系学生-千叶', 3, 3],
            '浅浅寂寞' => ['tours-info-laurent', 3, 3],
            'Silence' => ['深圳-学生-Silence', 3, 2.5],
            'sophie' => ['保定-孟影', 3, 3],
            '宋某某' => ['巴黎学生宋', 0, 0],
            '神之小不贰' => ['上海-搬砖工-不贰', 3, 3],
            'sherry' => ['广州-学生-sherry', 2, 2],
            'vivi\'s' => ['vivi\'s', 3, 3],
            'Hélène' => ['', 3, 2],
            '王珏Joyce' => ['雷恩-学生-Joyce', 3, 3],
            'weijiuer' => ['巴黎-学生-Nina', 3, 3],
            '我觉得有必要改哈名字' => ['贡比涅-学生-ycj', 2, 2 ],
            '我是你遗失多年的小伙伴' => ['南锡+商学狗+陈美美', 2, 2],
            '无畏亦是Arina' => ['无畏亦是Arina', 0, 0],
            '小艾达的花' => ['苏州-学生-Lila', 3, 3],
            '兮琳' => ['巴黎 待业 兮琳', 3, 3.5],
            '西门夫人' => ['长沙-学生-西门', 2, 3],
            '卤蛋' => ['武汉 学生 卤蛋', 2,  3],
            '宣宣进化了' => ['图卢兹 学生 宣宣', 3, 3.5],
            '许悦' => ['重庆 - 学生 - 许悦', 3, 4],
            '只可意卉儿' => ['北京-项目经理-只可意卉儿', 3, 2],
            '秀吉之野望' => ['巴黎-程序员-秀吉之野望', 3, 3.5],
            '徐必过' => ['巴黎 学生 上上', 3, 0]
        ];


        foreach($users as $key => $user) {
            Redis::set('90days:students:' . $key, $user[0]);
            if($user[1] != 0) {
                Redis::set('20161101:' . $key . ":score", $user[1]);
            }
            if($user[2] != 0) {
                Redis::set('20161102:' . $key . ":score", $user[2]);
            }
        }
    }

    /**
     * 周报
     */
    public function weekly()
    {
        $keys = Redis::keys('90days:students:*');
        $preLen = strlen('90days:students:');
        $users = [];
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = date("m-d", time() - 60 * 60 * 24 * $i);
            $days[] = $day;
        }

        foreach ($keys as $key) {
            $name = substr($key, $preLen);
            for ($i = 6; $i >= 0; $i--) {
                $day = date("Ymd", time() - 60 * 60 * 24 * $i);
                $score = Redis::get($day . ':' . $name . ':score');
                if ($score != '') {
                    $users[$name][] = [
                        'day' => $day,
                        'score' => $score
                    ];
                } else {
                    $users[$name][] = [
                        'day' => $day,
                        'score' => 0
                    ];
                }
            }
        }
        return view('admin.day90.weekly', compact('users', 'days'));
    }

    /**
     * 日报
     */
    public function daily($date = null)
    {
        if($date == null) {
            $today = date('Ymd');
        } else {
            $today = $date;
        }
        $keys = Redis::keys('90days:students:*');
        $users = [];
        $preLen = strlen('90days:students:');
        foreach ($keys as $key) {
            $name = substr($key, $preLen);
            $score = Redis::get($today . ':' . $name . ':score');
            if ($score != '') {
                $comment = Redis::get($today . ':' . $name . ':comment');
                $users[] = [
                    'name' => Redis::get($key),
                    'score' => $score,
                    'comment' => $comment
                ];
            }
        }
        return view('admin.day90.daily', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $today = date('Ymd');
        $today = $request->get('today');
        $prefix = $today . ":" . $request->get('name') . ':';
        Redis::set($prefix . 'score', $request->get('score'));
        Redis::set($prefix . 'comment', $request->get('comment'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function students()
    {

        $prefix = '90days:students:';
        $students = Redis::keys($prefix . '*');
        $len = strlen($prefix);
        $names = array_map(function ($student) use ($len, $prefix) {
            $wechatName = substr($student, $len);
            $nickName = Redis::get($prefix . $wechatName);
            $scores = Redis::keys('*:' . $wechatName . ':score');
            $infos = [];
            foreach($scores as $score) {
                $date = substr($score, 0, 8);
                $infos[] = [
                    'date' => $date,
                    'score' => Redis::get($score),
                    'comment' => Redis::get($date . ':' . $wechatName . ':comment')
                ];
            }
            return [
                'name' => $wechatName,
                'nickname' => $nickName,
                'info' => $infos
            ];
        }, $students);
        return response()->json($names);
    }
}
