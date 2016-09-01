<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Task;
use App\Video;

use App\Http\Requests;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $levels = ['beginner', 'intermediate', 'advanced'];

        if($request->has('type')) {
            $builder = Video::where('state', 1);
        } else {
            $builder = Video::where('state', 2);
        }
        if($request->has('level')) {
            $builder->where('level', $request->get('level'));
        }
        $videos = $builder->select(['slug', 'originSrc', 'duration', 'level', 'id', 'state', 'avatar', 'title', 'created_at'])->paginate(50);

        return view('tasks.index', compact('videos', 'levels'));
    }

    public function show(Request $request, $userId)
    {
        $builder = DB::table('tasks')->join('videos', 'videos.id', '=', 'tasks.video_id')->where('tasks.user_id', $userId);
        if ($request->has('type')) {
            //0: listen, 1: checkfr, 2: translate, 3: checkzh
            $type = $request->get('type');
            if(!is_numeric($type) || $type < 0 || $type > 4) {
                abort(404);
            }
            $builder->where('tasks.type', $type);
        } else {
            $builder->where('tasks.type', '2');
        }
        $videos = $builder->orderBy('videos.state')->orderBy('tasks.id')->select(['tasks.id', 'videos.slug', 'video_id', 'user_id', 'videos.state', 'videos.avatar', 'title', 'tasks.created_at', 'tasks.is_submit', 'videos.originSrc'])->paginate(50);

        return view('tasks.myTasks', compact('videos'));
    }

    public function preview($videoId)
    {
        $readable = Video::findOrFail($videoId);
        $youtube = Auth::user()->last_login_foreign;
        return view('tasks.preview', compact('readable', 'youtube'));
    }

    public function translate($videoId)
    {
        $readable = Video::findOrFail($videoId);
        $user = Auth::user();
        $task = Task::where('video_id', $videoId)->where('type', 2)->first();
        if($task) {
            if ($task->user_id != $user->id) {
                Session::flash('hasTranslator', 'hasTranslator');
                return redirect('translator/tasks');
            }
        } else {
            $readable->translator_id = $user->id;
            $readable->state = 3;

            $readable->save();
            $task = Task::create([
                'video_id' => $videoId,
                'user_id' => $user->id,
                'type' => 2,
                'content' => $readable->content,
            ]);
        }
        $youtube = $user->last_login_foreign;
        return view('tasks.translate', compact('readable', 'task', 'youtube'));
    }

    public function checkFr($videoId)
    {
        $readable = Video::findOrFail($videoId);
        $user = Auth::user();
        $task = Task::where('video_id', $videoId)->first();
        if($task) {
            if ($task->user_id != $user->id) {
                Session::flash('hasListener', 'hasListener');
                return redirect('translator/tasks?type=1');
            }
        } else {
            $readable->listener_id = $user->id;
            $readable->state = 7;
            $readable->save();

            $task = Task::create([
                'video_id' => $videoId,
                'user_id' => $user->id,
                'type' => 1,
                'content' => $readable->content,
            ]);
        }
        return view('tasks.translate', compact('readable', 'task'));
    }

    public function giveup($videoId)
    {
        $user = Auth::user();
        $task = Task::where('video_id', $videoId)->where('user_id', $user->id)->first();
        if($task) {
            $video = Video::find($videoId);
            $video->state = 2;
            $video->save();
            $task->delete();
        }
        return redirect('translator/tasks/' . $user->id);
    }

    public function save(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->content = $request->get('content', '');
        $task->save();
        $readable = $task->video;

        return view('tasks.translate', compact('readable', 'task'));
    }

    public function autoSave(Request $request, $taskId)
    {
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
        $video->state = 4;
        $video->save();

        Session::flash('successSubmit', '1');
        return redirect('translator/tasks');
    }

    public function submitFr(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->content = $request->get('content', '');
        $task->is_submit = true;
        $task->save();

        $video = $task->video;
        $video->state = 2;
        $content = Helper::filterSpecialChars($task->content);
        $video->content = $content;
        list($video->parsed_content, $video->parsed_content_zh, $video->points) = Helper::parsePointLink($content);
        $video->save();

        Session::flash('successSubmit', '1');
        return redirect('translator/tasks?type=1');
    }

    public function submitForce(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->content = $request->get('content', '');
        $task->is_submit = true;
        $task->save();

        $video = $task->video;
        $video->state = 5;

        $content = Helper::filterSpecialChars($task->content);
        $video->content = $content;

        list($video->parsed_content, $video->parsed_content_zh, $video->points) = Helper::parsePointLink($content);
        $video->save();

        Session::flash('successSubmit', '1');
        return redirect('translator/tasks');
    }
}
