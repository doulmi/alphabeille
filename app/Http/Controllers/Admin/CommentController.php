<?php

namespace App\Http\Controllers\Admin;

use App\Commentable;
use App\Lesson;
use App\Minitalk;
use App\Talkshow;
use Carbon\Carbon;
use Faker\Factory;
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
        $limit = 25;
        $comments = Commentable::where('commentable_type', 'App\\' . ucfirst($type))->paginate($limit)->appends($request->all());;

        return view('admin.comments.index', compact('type', 'comments'));
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
        return view('admin.comments.show', compact('content_id', 'edit', 'type'));
    }

    public function createMinitalks($minitalk_id) {
        $edit = false;
        $type = 'minitalkComments';
        $content_id = $minitalk_id;
        return view('admin.comments.show', compact('content_id', 'edit', 'type'));
    }


    public function createTalkshows($talkshow_id) {
        $edit = false;
        $type = 'talkshowComments';
        $content_id = $talkshow_id;
        return view('admin.comments.show', compact('content_id', 'edit', 'type'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeLessons(Request $request)
    {
        return $this->storeComments($request, 'App\Lesson', 'Lesson');
    }

    public function storeMinitalks(Request $request)
    {
        return $this->storeComments($request, 'App\Minitalk', 'Minitalk');
    }

    public function storeTalkshows(Request $request)
    {
        return $this->storeComments($request, 'App\Talkshow', 'Talkshow');
    }

    public function storeVideos(Request $request)
    {
        return $this->storeComments($request, 'App\Video', 'Video');
    }

    public function storeComments(Request $request, $type, $model) {
        $comments = explode("\r\n", $request->get('content'));
        $userIds = explode(" ", $request->get('user_id'));

        $i = 0;
        $content = $model::findOrFail($request->get('content_id'));
        $faker = Factory::create();
        $now = Carbon::now();
        foreach ($comments as $comment) {
            if($comment != "") {
                $c = Commentable::create([
                    'commentable_id' => $content->id,
                    'commentable_type' => $type,
                    'user_id' => $userIds[$i],
                    'content' => $comment,
                    'created_at' => Carbon::createFromTimestamp($faker->dateTimeBetween('- ' . $now->diffInDays($content->created_at) . ' days', 'now')->getTimestamp())
                ]);
                dd($faker->dateTimeBetween('- ' . $now->diffInDays($content->created_at) . ' days', 'now')->getTimestamp(), $c);

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
    public function destroy($type, $id)
    {
        Commentable::destroy($id);
    }
}
