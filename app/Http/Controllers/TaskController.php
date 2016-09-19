<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Task;
use App\UserTraces;
use App\Video;

use App\Http\Requests;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('verifier', ['checkZhIndex', 'checkZh', 'submitZh']);
    }

    public function index(Request $request, $type)
    {
        $levels = ['beginner', 'intermediate', 'advanced'];
        $builder = Video::with('translator')->where('state', $type);

        if ($request->has('level')) {
            $builder->where('level', $request->get('level'));
        }
        $videos = $builder->select(['slug', 'translator_id', 'originSrc', 'duration', 'level', 'id', 'state', 'avatar', 'title', 'created_at'])->paginate(50)->appends($request->all());

        return view('tasks.index', compact('videos', 'levels', 'type'));
    }

    public function checkZhIndex(Request $request)
    {
        return $this->index($request, VIDEO::WAIT_CHECK_ZH);
    }

    public function translateIndex(Request $request)
    {
        return $this->index($request, VIDEO::WAIT_TRANSLATE);
    }

    public function checkFrIndex(Request $request)
    {
        return $this->index($request, VIDEO::WAIT_CHECK_FR);
    }

    public function checkFrTasks(Request $request, $userId)
    {
        return $this->show($request, $userId, Task::CHECK_FR);
    }

    public function checkZhTasks(Request $request, $userId)
    {
        return $this->show($request, $userId, Task::CHECK_ZH);
    }

    public function translateTasks(Request $request, $userId)
    {
        return $this->show($request, $userId, Task::TRANSLATE);
    }

    public function show(Request $request, $userId, $type)
    {
        $builder = DB::table('tasks')->join('videos', 'videos.id', '=', 'tasks.video_id')->join('users', 'users.id', '=', 'tasks.user_id')->where('tasks.user_id', $userId);
        $builder->where('tasks.type', $type);
        $videos = $builder->orderBy('videos.state')->orderBy('tasks.id')->select(['tasks.id', 'videos.slug', 'video_id', 'user_id', 'users.name', 'videos.state', 'videos.avatar', 'title', 'tasks.created_at', 'tasks.is_submit', 'videos.originSrc'])->paginate(50)->appends($request->all());;

        return view('tasks.myTasks', compact('videos'));
    }

    public function preview($videoId)
    {
        $readable = Video::findOrFail($videoId);
        $youtube = Auth::user()->last_login_foreign;
        $type = 'video';
        return view('tasks.preview', compact('readable', 'youtube', 'type'));
    }

    public function translate($videoId)
    {
        $readable = Video::findOrFail($videoId);
        $user = Auth::user();
        $task = Task::where('video_id', $videoId)->where('type', 2)->first();
        if ($task) {
            if ($task->user_id != $user->id) {
                Session::flash('hasTranslator', 'hasTranslator');
                return redirect('translator/tasks/translate');
            }
        } else {
            UserTraces::translate('App\Video', $videoId);

            $readable->translator_id = $user->id;
            $readable->state = Video::ON_TRANSLATE;

            $readable->save();
            $task = Task::create([
                'video_id' => $videoId,
                'user_id' => $user->id,
                'type' => Task::TRANSLATE,
                'content' => $readable->content,
            ]);
        }
        $youtube = $user->last_login_foreign;
        $type = 'video';
        return view('tasks.translate', compact('readable', 'task', 'youtube', 'type'));
    }

    public function checkZh($videoId)
    {
        $user = Auth::user();
        $task = Task::where('video_id', $videoId)->where('type', Task::CHECK_ZH)->first();
        $readable = Video::where('id', $videoId)->first();

        if ($task) { //已经存在了
            if($task->user_id != $user->id) {   //但任务归属人并不是此人
                Session::flash('hasTranslator', 'hasVerifier');
                return redirect('translator/tasks/checkZh');
            }
        } else {    //还不存在，需要创建一个新任务
            $task = Task::where('video_id', $videoId)->where('type', Task::TRANSLATE)->first();
            $readable = Video::with('translator')->where('id', $videoId)->first();

            if ($task) {
                $content = $task->content;
            } else {
                $content = $readable->content;
            }

            UserTraces::checkZh('App\Video', $videoId);
            $readable->verifier_id = $user->id;
            $readable->state = Video::ON_CHECK_ZH;
            $readable->save();

            $task = Task::create([
                'video_id' => $videoId,
                'user_id' => Auth::id(),
                'type' => Task::CHECK_ZH,
                'content' => $content
            ]);
        }

        $youtube = $user->last_login_foreign;
        $type = 'video';
        return view('tasks.translate', compact('readable', 'task', 'youtube', 'type'));
    }

    public function checkFr($videoId)
    {
        $readable = Video::findOrFail($videoId);
        $user = Auth::user();
        $task = Task::where('video_id', $videoId)->first();
        if ($task) {
            if ($task->user_id != $user->id) {
                Session::flash('hasListener', 'hasListener');
                return redirect('translator/tasks?type=1');
            }
        } else {
            UserTraces::checkFr('App\Video', $videoId);

            $readable->listener_id = $user->id;
            $readable->state = Video::ON_CHECK_FR;
            $readable->save();

            $task = Task::create([
                'video_id' => $videoId,
                'user_id' => $user->id,
                'type' => Task::CHECK_FR,
                'content' => $readable->content,
            ]);
        }
        $type = 'video';
        $youtube = $user->last_login_foreign;
        return view('tasks.translate', compact('readable', 'task', 'youtube', 'type'));
    }

    public function giveupTranslate($taskId) {
        return $this->giveup($taskId, Video::WAIT_TRANSLATE);
    }

    public function giveupCheckZh($taskId) {
        return $this->giveup($taskId, Video::WAIT_CHECK_ZH);
    }

    public function giveup($taskId, $state)
    {
        $user = Auth::user();
        $task = Task::where('id', $taskId)->where('user_id', $user->id)->first();
        if ($task) {
            $video = Video::find($task->video_id);
            $video->state = $state;
            $video->save();
            $task->delete();
        }
        return redirect()->back();
    }

    public function save(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->content = $request->get('content', '');
        $task->save();
        $readable = $task->video;
        $youtube = Auth::user()->last_login_foreign ? true : false;

        $type = 'video';
        return view('tasks.translate', compact('readable', 'task', 'youtube', 'type'));
    }

    public function autoSave(Request $request, $taskId)
    {
        if (Auth::user() && (Auth::user()->isTranslator() || Auth::user()->can('videos.translate'))) {
            $task = Task::find($taskId);
            if ($task) {
                $user = Auth::user();
                if ($user->id == $task->user_id) {
                    $task->content = $request->get('content', '');
                    $task->save();
                    return response()->json([
                        'state' => 200,
                    ]);
                }
            }
        }

        return response()->json([
            'state' => 403,
        ]);
    }


    public function submit(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->content = $request->get('content', '');
        $task->is_submit = true;
        $task->save();

        $video = $task->video;
        $video->state = Video::WAIT_CHECK_ZH;
        $video->save();

        UserTraces::submitTranslate('App\Video', $video->id);
        Session::flash('successSubmit', '1');
        return redirect('translator/tasks/translate');
    }

    public function submitFr(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->content = $request->get('content', '');
        $task->is_submit = true;
        $task->save();

        $video = $task->video;
        $video->state = Video::WAIT_TRANSLATE;

        $content = Helper::filterSpecialChars($task->content);
        $video->content = $content;

        UserTraces::submitCheckFr('App\Video', $video->id);
        list($video->parsed_content, $video->parsed_content_zh, $video->points) = Helper::parsePointLink($content);
        $video->save();

        Session::flash('successSubmit', '1');
        return redirect('translator/tasks/checkFr');
    }

    public function submitZh(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->content = $request->get('content', '');
        $task->is_submit = true;
        $task->save();

        $video = $task->video;
        $video->state = Video::OK;

        $content = Helper::filterSpecialChars($task->content);
        $video->content = $content;

        UserTraces::validTranslate('App\Video', $video->id);
        list($video->parsed_content, $video->parsed_content_zh, $video->points) = Helper::parsePointLink($content);
        $video->save();

        Session::flash('successSubmit', '1');
        return redirect('translator/tasks/checkZh');
    }
}
