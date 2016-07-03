<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Discussion;
use App\Events\At;
use App\Lesson;
use App\LessonComment;
use App\User;
use App\UserCommentLike;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LessonCommentController extends Controller
{

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $comments = LessonComment::with('owner')->where('lesson_id', $id)->get();
        foreach($comments as $comment) {
            $comment->userId = $comment->owner->id;
            $comment->avatar = $comment->owner->avatar;
            $comment->name = $comment->owner->name;
        }
        return $comments;
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
        $lesson = Lesson::findOrFail($request->get('lesson_id'));
        $content = $request->get('content');
        $user = Auth::user();

        LessonComment::create([
            'lesson_id' => $lesson->id,
            'user_id' => $user->id,
            'content' => $content
        ]);

        return response()->json([
            'status' => 200,
            'content' => $content,
            'user_id' => $user->id,
            'lesson_id' => $lesson->id
        ]);

//        return redirect('lessons/' . $lesson->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    public function like($id)
    {
        $comment = Comment::with('likes')->findOrFail($id);

        if ($comment) {
            $user = Auth::user();
            $liked = $comment->likes()->where('user_id', $user->id)->count() > 0 ? true : false;
            if (!$liked) {
                UserCommentLike::create([
                    'user_id' => Auth::user()->id,
                    'comment_id' => $id,
                ]);
                $message = 'like';
            } else {
                UserCommentLike::where(['user_id' => $user->id, 'comment_id' => $id])->delete();
                $message = 'unlike';
            }
            return response()->json([
                'status' => 200,
                'data' => [
                    'message' => $message
                ]
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'data' => [
                    'message' => trans('labels.likeError')
                ]
            ]);
        }
    }
}
