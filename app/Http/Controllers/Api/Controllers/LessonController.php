<?php

namespace App\Http\Controllers\Api\Controllers;

use App\Http\Controllers\Api\Transformers\LessonTransformer;
use App\Lesson;
use Illuminate\Http\Request;
use JWTAuth;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class LessonController extends BaseApiController
{
    private $selectedCols = ['id', 'title', 'description', 'order', 'likes', 'free', 'views', 'audio_url', 'download_url', 'created_at', 'duration'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($topicId)
    {

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lesson = Lesson::find($id, $this->selectedCols);
        if (!$lesson) {
            $this->response->errorNotFound();
        } else {

        }

        $result = [
            'id' => $lesson->id,
            'title' => $lesson->title,
            'description' => $lesson->description,
            'likes' => $lesson->likes,
            'views' => $lesson->views,
            'free' => $lesson->free,
            'avatar' => $lesson->avatar,
            'duration' => $lesson->duration,
            'created_at' => $lesson->create_at
        ];

        if ($lesson->free) {
            return array_merge($result, [
                'audio_url' => $lesson->audio_url,
                'download_url' => $lesson->download_url
            ]);
        }
        $token = Input::get('token');
        if ($token) {
            $user = JWTAuth::toUser($token);
            if ($user->level() > 1) {
                return array_merge($result, [
                    'audio_url' => $lesson->audio_url,
                    'download_url' => $lesson->download_url
                ]);
            }
        }
        return $result;
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
}
