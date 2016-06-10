<?php

namespace App\Http\Controllers;

use App\Discussion;
use App\Editor\Markdown\Markdown;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class DiscussionController extends Controller
{
    protected $editor;

    public function __construct(Markdown $editor)
    {
        $this->middleware('auth', ['except' => [
            'index', 'show'
        ]]);

        $this->editor = $editor;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = Input::get('limit', Config::get('pagination')['forum.pageLimit']);
        $discussions = Discussion::with(['owner', 'lastAnswerBy', 'comments'])->orderBy('fixtop_expire_at', 'DESC')->latest()->paginate($limit);

        $count = Discussion::count();

        return view('forum.index', compact(['discussions', 'count']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('forum.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $userId = Auth::user()->id;
        $discussion = Discussion::create(array_merge($request->all(), [
            'user_id' => $userId,
            'last_answer_by' => $userId
        ]));
        if($discussion) {
            Session::flash('success', trans('labels.successAddDiscussion'));
            return redirect('discussions');
        } else {
            Session::flash('errors', trans('labels.failAddDiscussion'));
            return view('forum.create')->withInput($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $discussion = Discussion/*::with('comments')*/::findOrFail($id);
        $comments = $discussion->comments()->latest()->get();
        foreach($comments as $comment) {
            $comment->likesNum = count($comment->likes);
        }
//
        $html = $discussion->content;
//        $comments = $comments->sortByDesc('likesNum');
        if ($discussion) {
            return view('forum.show', compact(['discussion', 'html', 'comments']));
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
        $discussion = Discussion::findOrFail($id);
        if ($discussion) {
            return view('forum.discussion', compact('discussion'));
        } else {
            abort(404);
        }
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
        $discussion = Discussion::findOrFail($id);
        $discussion->content = $request->get('content');
        $discussion->title = $request->get('title');
        $discussion->save();
        Session::flash('success', trans('labels.successUpdateDiscussion'));
        return view('forum.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Discussion::destroy($id);
    }
}
