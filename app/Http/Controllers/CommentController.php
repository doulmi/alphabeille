<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Discussion;
use App\Events\At;
use App\User;
use App\UserCommentLike;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $discussion = Discussion::findOrFail($request->get('discussion_id'));
        if($discussion) {
            return DB::transaction(function () use ($request, $discussion) {
                $content = $request->get('content');
                $user = Auth::user();

                Comment::create([
                    'discussion_id' => $discussion->id,
                    'user_id' => $user->id,
                    'content' => $content
                ]);

                $discussion->last_answer_by = $user->id;
                $discussion->save();

                $find = preg_match('/<a href=\"\/users\/(\d*)\">@/', $content, $output_array);


                if($find) {
                    $target = User::findOrFail($output_array[1]);
                    if($target) {
                        event(new At($target, $discussion));
                    }
                } else {
                    event(new At($user, $discussion));
                }
                return redirect('discussions/' . $discussion->id);
            });
        } else {
            abort(404);
        }
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
            if(!$liked) {
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
