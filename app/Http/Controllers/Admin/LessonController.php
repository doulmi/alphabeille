<?php

namespace App\Http\Controllers\Admin;

use App\Helper;
use App\Lesson;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orderBy = Input::get('orderBy', 'created_at');
        $dir = Input::get('dir', 'DESC');
        $limit = Input::get('limit', 50);
        $search = trim(Input::get('search', ''));
        $searchField = trim(Input::get('searchField', ''));

        if ($searchField != '' && $search != '') {
            if ($searchField != 'role') {
                $lessons = Lesson::published()->where($searchField, 'LIKE', "%$search%")->orderBy($orderBy, $dir)->paginate($limit);
            }
        } else {
            $lessons = Lesson::published()->orderBy($orderBy, $dir)->paginate($limit);
        }
        return view('admin.lessons', compact(['lessons']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($topicId)
    {
        $edit = false;
        return view('admin.lesson', compact('topicId', 'edit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Lesson::create($request->all());

        Redis::incr('audio:count');
        Session::flash('success', trans('labels.createLessonSuccess'));
        return redirect('admin/lessons');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lesson = Lesson::firstOrFail($id);
        return $lesson;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $edit = true;
        $lesson = Lesson::findOrFail($id);
        return view('admin.lesson', compact('edit', 'lesson'));
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
        $lesson= Lesson::find($id);
        $lesson->update($request->all());
        return redirect('admin/lessons');
//        $lesson->update([
//            'title' => $request->get('title'),
//            'avatar' => $request->get('avatar'),
//            'content' => $request->get('content'),
//            'content_zh_CN' => $request->get('content_zh_CN'),
//            'free' => $request->get('free'),
//            'likes' => $request->get('likes'),
//            'views' => $request->get('views'),
//            'topic_id' => $request->get('topic_id'),
//            'duration' => $request->get('duration'),
//            'audio_url' => $request->get('audio_url'),
//            'audio_url_zh_CN' => $request->get('audio_url_zh_CN'),
//            'download_url' => $request->get('download_url'),
//        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id);
        $lesson->delete();
        return response()->json([
            'status' => 200
        ]);
    }
}
