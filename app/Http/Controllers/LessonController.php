<?php

namespace App\Http\Controllers;

use App\Editor\Markdown\Markdown;
use App\Lesson;
use App\Topic;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;

class LessonController extends Controller
{
    private $viewMax = 100;
    private $pageLimit = 24;
    private $markdown;

    /**
     * @param $makrdown
     */
    public function __construct(Markdown $markdown) {
        $this->markdown = $markdown;
    }
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
     * @param int $lan : fr, zh_CN
     * @return \Illuminate\Http\Response
     */
    public function show($id, $lan = 'fr')
    {
        $lesson = Lesson::findOrFail($id);
        $topicController = new TopicController();
        Redis::incr('lesson:view:' . $id);
//        $views = Redis::get('lesson:view:' . $id);

        //每100次访问，才更新一次数据库
//        if ($views == $this->viewMax) {
//            $lesson->views += $this->viewMax;
//            $lesson->save();
//        }
        $topic = $lesson->topic;
        Redis::incr('topic:view:' . $topic->id);

        $topics = $topicController->random();
//        $comments = $lesson->comments;

        if($lan == 'fr') {
            $content = $this->markdown->parse("####" .$lesson->content);
        } else if($lan == 'zh_CN') {
            $lesson->audio_url = $lesson->audio_url_zh_CN;
            $content = $this->markdown->parse("####" .$lesson->content_zh_CN);
        } else {
            abort(404);
        }
        return view('lessons.show', compact(['lesson', 'topic', 'id', 'topics', 'content']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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

    }
}
