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
    public function index()
    {
        $levels = ['beginner', 'intermediate', 'advanced'];
        $videos = Video::where('state', 2)->paginate(50);

        return view('tasks.index', compact('videos', 'levels'));
    }

    public function show($userId)
    {
        $videos = DB::table('tasks')->join('videos', 'videos.id', '=', 'tasks.video_id')->where('tasks.user_id', $userId)->orderBy('tasks.id')->orderBy('videos.state')->select(['tasks.id', 'video_id', 'user_id', 'videos.state', 'videos.avatar', 'title', 'tasks.created_at', 'tasks.is_submit'])->paginate(50);

        return view('tasks.myTasks', compact('videos'));
    }

    public function preview($videoId)
    {
        $readable = Video::findOrFail($videoId);
        return view('tasks.preview', compact('readable'));
    }

    public function translate($videoId)
    {
        $readable = Video::findOrFail($videoId);
        $user = Auth::user();
        $task = Task::where('video_id', $videoId)->first();
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
                'content' => $readable->content,
            ]);
        }
        return view('tasks.translate', compact('readable', 'task'));
    }

    public function save(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->content = $request->get('content', '');
        $task->save();
        $readable = $task->video;

        return view('tasks.translate', compact('readable', 'task'));
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

    public function submitForce(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->content = $request->get('content', '');
        $task->is_submit = true;
        $task->save();

        $video = $task->video;

        $video->state = 5;

        $content = str_replace('！', '!', $task->content);
        $content = str_replace('？', '?', $content);
        $content = str_replace('  ', ' ', $content);
        $content = str_replace('‘', '\'', $content);
        $content = str_replace('’', '\'', $content);
        $content = str_replace('“', '\'', $content);
        $content = str_replace('”', '\'', $content);
        $content = str_replace('"', '\'', $content);
        $content = str_replace('。', '.', $content);
        $content = str_replace('，', ',', $content);
        $content = str_replace('…', '...', $content);
        $content = str_replace('!', '.', $content);
        $content = str_replace('\n\n', '\n', $content);
        $content = str_replace(' ', ' ', $content);//特殊的空格,会被看做中文
        $content = str_replace('–', '-', $content);
        $content = str_replace('♪', '', $content);
        $video->content = $content;

        list($video->parsed_content, $video->parsed_content_zh, $video->points) = Helper::parsePointLink($content);
        $video->save();

        Session::flash('successSubmit', '1');
        return redirect('translator/tasks');
    }
}
