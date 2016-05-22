<?php

namespace App\Http\Controllers;

use App\Lesson;
use App\Talkshow;
use App\Topic;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lesson = Lesson::with('topic')->findOrFail($id);
        $topic = $lesson->topic;

        if($lesson) {
            $topicController = new TopicController();
            $talkshowController = new TalkshowController();

            $topics = $topicController ->random();
            $talkshows = $talkshowController->random();
            return view('lessons.show', compact(['lesson', 'topic', 'id', 'topics', 'talkshows']));
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
