<?php

namespace App\Http\Controllers\Admin;

use App\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Task;
use App\User;
use App\UserTraces;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $builder = DB::table('tasks')->join('videos', 'videos.id', '=', 'tasks.video_id')->join('users', 'users.id', '=', 'tasks.user_id');

        $types = [7, 3];
        $reverse = [
            3 => 2,
            7 => 1,
        ];
        if ($request->has('state')) {
            $state = $request->get('state');
            switch ($state) {
                case 0 :
                    $builder->where('tasks.is_submit', 0);
                    break;
                case 1 :
                    $builder->where('tasks.is_submit', 1)->where('videos.state', 4);
                    break;
                case 2 :
                    $builder->whereIn('videos.state', [5, 6]);
                    break;
            }
        }
        if ($request->has('type')) {
            $builder->where('tasks.type', $reverse[$request->get('type')]);
        }

        if ($request->get('doubt')) {
            $builder->where('tasks.trouble', 1);
        }

        if ($request->has('user')) {
            $userId = $request->get('user');
            $builder->where('tasks.user_id', $userId);
            $trans = User::findOrFail($userId);
        }

        $translators = DB::table('role_user')->leftJoin('users', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '<>', '4')->get(['users.id', 'users.name']);

        $videos = $builder->select(['tasks.id', 'video_id', 'videos.slug', 'user_id', 'videos.state', 'videos.avatar', 'title', 'tasks.type', 'tasks.created_at', 'tasks.updated_at', 'users.name', 'videos.duration', 'tasks.is_submit'])->orderBy('tasks.updated_at', 'DESC')->paginate(50)->appends($request->all());

        return view('admin.tasks.index', compact('videos', 'types', 'translators', 'trans'));
    }

    public function translate($taskId)
    {
        $task = Task::with('user')->findOrFail($taskId);
        $readable = Video::findOrFail($task->video_id);
        $type = 'video';
        return view('admin.tasks.translate', compact('readable', 'task', 'type'));
    }

    public function save(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->content = $request->get('content', '');
        $task->save();
        $readable = $task->video;
        $type = 'video';

        return view('admin.tasks.translate', compact('readable', 'task', 'type'));
    }

    public function autoSave(Request $request, $taskId)
    {
        if (Auth::user() && Auth::user()->isAdmin()) {
            $task = Task::find($taskId);
            if ($task) {
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

        UserTraces::submitTranslate('App\Video', $video->id);
        Session::flash('successSubmit', '1');
        return redirect('admin/tasks');
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

        UserTraces::validTranslate('App\Video', $video->id);
        list($video->parsed_content, $video->parsed_content_zh, $video->points) = Helper::parsePointLink($content);
        $video->save();

        Session::flash('successSubmit', '1');
        return redirect('admin/tasks?state=1&&type=3');
    }

    public function doubt($task_id)
    {
        $task = Task::find($task_id);
        if ($task) {
            $task->trouble = !$task->trouble;
            $task->save();
        }
    }
}
