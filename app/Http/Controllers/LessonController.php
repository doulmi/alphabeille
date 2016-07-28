<?php

namespace App\Http\Controllers;

use App\Commentable;
use App\Editor\Markdown\Markdown;
use App\Lesson;
use App\LessonCollect;
use App\LessonFavorite;
use App\Topic;
use App\UserPunchin;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class LessonController extends ReadableController
{
    private $viewMax = 100;
    private $pageLimit = 24;
    private $markdown;

    /**
     * @param $makrdown
     */
    public function __construct(Markdown $markdown)
    {
        $this->markdown = $markdown;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lessons = Lesson::orderby('id', 'DESC')->paginate($this->pageLimit);
        return view('lessons.index', compact('lessons'));
    }

    public function latest($num)
    {
        $lessons = Lesson::orderBy('id', 'DESC')->limit($num)->get();
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
    public function show($idOrSlug, $lan = 'fr')
    {
        $lesson = Lesson::findByIdOrSlugOrFail($idOrSlug);

        $topicController = new TopicController();
        $id = $lesson->id;
        Redis::incr('lesson:view:' . $id);

        $topic = $lesson->topic;
        Redis::incr('topic:view:' . $topic->id);

        $topics = $topicController->random();

        if ($lan == 'fr') {
            $content = $lesson->parsed_content;
        } else if ($lan == 'zh_CN') {
            $lesson->audio_url = $lesson->audio_url_zh_CN;
            $content = $lesson->parsed_content_zh_CN;
        } else {
            abort(404);
        }

        list($like, $collect, $punchin) = $this->getStatus($lesson);

        $readable = $lesson;
        $type = 'lesson';
        return view('lessons.show', compact(['readable', 'type', 'topic', 'id', 'topics', 'content', 'like', 'collect', 'punchin']));
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

    public function free()
    {
        $lessons = Lesson::where('free', 1)->orderBy('id', 'DESC')->paginate($this->pageLimit);
        return view('lessons.index', compact('lessons'));
    }

    public function collectLessons()
    {
        $lessons = LessonCollect::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->paginate($this->pageLimit);
        return view('lessons.index', compact('lessons'));
    }


}
