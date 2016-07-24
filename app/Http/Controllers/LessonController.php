<?php

namespace App\Http\Controllers;

use App\Editor\Markdown\Markdown;
use App\Lesson;
use App\LessonCollect;
use App\LessonFavorite;
use App\Topic;
use App\UserPunchin;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
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
    public function show($id, $lan = 'fr')
    {
        $lesson = Lesson::findByIdOrSlugOrFail($id);

        $topicController = new TopicController();
        Redis::incr('lesson:view:' . $id);

        $topic = $lesson->topic;
        Redis::incr('topic:view:' . $topic->id);

        $topics = $topicController->random();

        if ($lan == 'fr') {
            $content = $this->markdown->parse("####" . $lesson->content);
        } else if ($lan == 'zh_CN') {
            $lesson->audio_url = $lesson->audio_url_zh_CN;
            $content = $this->markdown->parse("####" . $lesson->content_zh_CN);
        } else {
            abort(404);
        }

        $like = false;
        $collect = false;
        $punchin = false;

        if (!Auth::guest()) {
            $model = LessonFavorite::where('user_id', Auth::user()->id)->where('lesson_id', $id)->first();
            if ($model) {
                $like = true;
            }
            $model = LessonCollect::where('user_id', Auth::user()->id)->where('lesson_id', $id)->first();
            if ($model) {
                $collect = true;
            }
            $model = UserPunchin::where('user_id', Auth::user()->id)->whereDate('created_at', '=', Carbon::today()->toDateString())->first();
            if ($model) {
                $punchin = true;
            }
        }

//        dd($like, $collect);
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

    public function favorite($id)
    {
        return $this->doAction($id, LessonFavorite::class);
    }

    public function collect($id)
    {
        return $this->doAction($id, LessonCollect::class);
    }

    public function punchin($id)
    {
        $user = Auth::user();
        if(!$user) {
            return response()->json([
                'status' => 403,
            ]);
        }
        $punchin = UserPunchin::where('user_id', $user->id)->whereDate('created_at', '=', Carbon::today()->toDateString())->first();
        if (!$punchin) {
            UserPunchin::create([
                'punchable_type' => 'App\Lesson',
                'punchable_id' => $id,
                'user_id' => $user->id
            ]);

            $break = false;
            $user->series++;
            $shouldUpdateMaxSeries = $user->series > $user->maxSeries;
            if ($shouldUpdateMaxSeries) {
                $user->maxSeries = $user->series;
                $break = true;
            }
            $user->save();
            return response()->json([
                'status' => 200,
                'break' => $break,
                'series' => $user->series
            ]);
        }
    }

    private function doAction($id, $class)
    {
        //不登录没权限
        if (Auth::guest()) {
            return response()->json([
                'status' => 403,
            ]);
        }

        $model = $class::where([
            'lesson_id' => $id,
            'user_id' => Auth::user()->id
        ])->first();

        //已经收藏或喜欢的话会取消
        if ($model) {
            $model->delete();

            return response()->json([
                'status' => 200
            ]);
        } else {
            $class::create([
                'lesson_id' => $id,
                'user_id' => Auth::user()->id
            ]);

            return response()->json([
                'status' => 200
            ]);
        }
    }


}
