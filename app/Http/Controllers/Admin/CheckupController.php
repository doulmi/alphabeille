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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * 周报
     */
    public function weekly() {
        $keys = Redis::keys('90days:students:*');
        $preLen = strlen('90days:students:');
        $users = [];
        $days = [];
        for($i = 6; $i >=0; $i --){
            $day = date("m-d", time() - 60 * 60 * 24 * $i);
            $days[] = $day;
        }

        foreach ($keys as $key) {
            $name = substr($key, $preLen);
            for($i = 6; $i >=0; $i --){
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
    public function daily()
    {
        $today = date('Ymd');
        $keys = Redis::keys('90days:students:*');
        $users = [];
        $preLen = strlen('90days:students:');
        foreach ($keys as $key) {
            $name = substr($key, $preLen);
            $score = Redis::get($today . ':' . $name . ':score');
            if ($score != '') {
                $comment = Redis::get($today . ':' . $name . ':comment');
                $users[] = [
                    'name' => $name,
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
        $today = date('Ymd');
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
    public function show($id)
    {
        //
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
            return [
                'name' => $wechatName,
                'nickname' => $nickName
            ];
        }, $students);
        return response()->json($names);
    }
}
