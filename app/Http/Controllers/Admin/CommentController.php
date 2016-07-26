<?php

namespace App\Http\Controllers\Admin;

use App\Lesson;
use App\LessonComment;
use App\MinitalkComment;
use App\TalkshowComment;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        if($type == 'lessons') {
//            LessonComment::
        }
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

    public function createLessons($lesson_id) {
        $edit = false;
        $type = 'lessonComments';
        $content_id = $lesson_id;
        return view('admin.comment', compact('content_id', 'edit', 'type'));
    }

    public function createMinitalks($minitalk_id) {
        $edit = false;
        $type = 'minitalkComments';
        $content_id = $minitalk_id;
        return view('admin.comment', compact('content_id', 'edit', 'type'));
    }


    public function createTalkshows($talkshow_id) {
        $edit = false;
        $type = 'talkshowComments';
        $content_id = $talkshow_id;
        return view('admin.comment', compact('content_id', 'edit', 'type'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeLessons(Request $request)
    {
        $comments = explode("\r\n", $request->get('content'));
        $userIds = explode(" ", $request->get('user_id'));

        $i = 0;
        foreach ($comments as $comment) {
            if($comment != "") {
                LessonComment::create([
                    'lesson_id' => $request->get('content_id'),
                    'user_id' => $userIds[$i],
                    'content' => $comment
                ]);

                $i ++;
            }
        }
        return view('admin.index');
    }

    public function storeMinitalks(Request $request)
    {
        $comments = explode("\r\n", $request->get('content'));
        $userIds = explode(" ", $request->get('user_id'));

        $i = 0;
        foreach ($comments as $comment) {
            if($comment != "") {
                MinitalkComment::create([
                    'minitalk_id' => $request->get('content_id'),
                    'user_id' => $userIds[$i],
                    'content' => $comment
                ]);

                $i ++;
            }
        }
        return view('admin.index');
    }

    public function storeTalkshows(Request $request)
    {
        $comments = explode("\r\n", $request->get('content'));
        $userIds = explode(" ", $request->get('user_id'));

        $i = 0;
        foreach ($comments as $comment) {
            if($comment != "") {
                TalkshowComment::create([
                    'talkshow_id' => $request->get('content_id'),
                    'user_id' => $userIds[$i],
                    'content' => $comment
                ]);

                $i ++;
            }
        }
        return view('admin.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
