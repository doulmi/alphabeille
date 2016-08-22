<?php

namespace App\Http\Controllers\Admin;

use App\Helper;
use App\Task;
use App\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TaskController extends Controller
{
    public function index(Request $request) {
        $builder = DB::table('tasks')->join('videos', 'videos.id', '=', 'tasks.video_id')->join('users', 'users.id', '=', 'tasks.user_id');

        if($request->has('state')) {
            $builder->where('videos.state', $request->get('state'));
        }

        if($request->has('type')) {
//            $builder->where('tasks.')
        }

        $types = [0, 1, 2, 3];

        $videos = $builder->select(['tasks.id', 'video_id', 'user_id', 'videos.state', 'videos.avatar', 'title', 'tasks.created_at', 'users.name'])->paginate(50);

        return view('admin.tasks.index', compact('videos', 'types'));
    }

    public function translate($taskId) {
        $task = Task::findOrFail($taskId);
        $readable = Video::findOrFail($task->video_id);
        return view('admin.tasks.translate', compact('readable', 'task'));
    }

    public function save(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->content = $request->get('content', '');
        $task->save();
        $readable = $task->video;

        return view('admin.tasks.translate', compact('readable', 'task'));
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

        list($video->parsed_content, $video->parsed_content_zh, $video->points) = Helper::parsePointLink($content);
        $video->save();

        Session::flash('successSubmit', '1');
        return redirect('admin/tasks');
    }
}
