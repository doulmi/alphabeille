<?php

namespace App\Http\Controllers;

use App\Lesson;
use App\Talkshow;
use App\Topic;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;

class LessonController extends Controller
{
    private $viewMax = 100;
    private $pageLimit = 24;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lessons = Lesson::latest()->paginate($this->pageLimit);
        return view('lessons.index', compact('lessons'));
    }

    public function latest($num)
    {
        $lessons = Lesson::latest()->limit($num)->get();
        return $lessons;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lesson = Lesson::findOrFail($id);
        $topicController = new TopicController();
        Redis::incr('lesson:view:' . $id);
        $views = Redis::get('lesson:view:' . $id);

        //每100次访问，才更新一次数据库
        if ($views == $this->viewMax) {
            Redis::set('lesson:view:' . $id, 0);
            $lesson->views += $this->viewMax;
            $lesson->save();
        }
        $topic = $lesson->topic;
        $topics = $topicController->random();
        $comments = $lesson->comments;

        return view('lessons.show', compact(['lesson', 'topic', 'id', 'topics', 'comments']));
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
}
