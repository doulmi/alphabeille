<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Discussion;
use App\Editor\Markdown\Markdown;
use App\Events\At;
use App\Lesson;
use App\LessonComment;
use App\Talkshow;
use App\TalkshowComment;
use App\User;
use App\UserCommentLike;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TalkshowCommentController extends Controller
{

    private $markdown;

    public function __construct(Markdown $markdown)
    {
        $this->markdown = $markdown;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $comments = TalkshowComment::with('owner')->where('talkshow_id', $id)->get();
        foreach($comments as $comment) {
            $comment->userId = $comment->owner->id;
            $comment->avatar = $comment->owner->avatar;
            $comment->name = $comment->owner->name;
            $comment->content = $this->markdown->parse("####" . $comment->content);
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
        $talkshow = Talkshow::findOrFail($request->get('id'));
        $content = $request->get('content');
        $user = Auth::user();

        TalkshowComment::create([
            'talkshow_id' => $talkshow->id,
            'user_id' => $user->id,
            'content' => $content
        ]);

        return response()->json([
            'status' => 200,
            'content' => $content,
            'user_id' => $user->id,
            'id' => $talkshow->id
        ]);
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
