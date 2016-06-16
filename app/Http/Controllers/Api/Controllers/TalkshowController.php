<?php

namespace App\Http\Controllers\Api\Controllers;

use App\Http\Controllers\Api\Transformers\TalkshowTransformer;
use App\Talkshow;
use Illuminate\Http\Request;
use JWTAuth;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class TalkshowController extends BaseApiController
{
    private $selectedCols = ['id', 'title', 'description', 'avatar', 'free', 'likes', 'views', 'download_url', 'audio_url', 'created_at', 'duration'];

    /**
     * Display a listing of the resource.
     *
     * @param $count : 每一页展现的内容数
     * @param $page : 页数
     * @return \Illuminate\Http\Response
     */
    public function index($count, $page)
    {
        $talkshows = Talkshow::latest()->paginate($count, $this->selectedCols, 'page', $page);
        return $this->response->paginator($talkshows, new TalkshowTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $talkshow = Talkshow::find($id, $this->selectedCols);
        if(!$talkshow){
            $this->response->errorNotFound();
        }

        $result = [
            'id' => $talkshow->id,
            'title' => $talkshow->title,
            'description' => $talkshow->description,
            'likes' => $talkshow->likes,
            'views' => $talkshow->views,
            'free' => $talkshow->free,
            'avatar' => $talkshow->avatar,
            'duration' => $talkshow->duration,
            'created_at' => $talkshow->create_at
        ];

        $token = Input::get('token');

        if($token) {
            $user = JWTAuth::toUser($token);
            if ($user->level() > 1 || $talkshow->free) {
                //user have no authentcation to see the audio_url
                return array_merge($result, [
                    'audio_url' => $talkshow->audio_url,
                    'download_url' => $talkshow->download_url
                ]);
            }
        }

        return $result;

//        return response()->json([
//            'level' => $user->level(),
//            'free' => $talkshow->free,
//            'url' => $talkshow->audioUrl
//        ]);

//        return
//        return $this->item($talkshow, new TalkshowTransformer());
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
