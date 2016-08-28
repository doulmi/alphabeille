<?php

namespace App\Http\Controllers\Api\Controllers;

use App\Http\Controllers\Api\Transformers\VideoTransformer;
use App\Video;
use Illuminate\Http\Request;
use JWTAuth;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class VideoController extends BaseApiController
{
    private $selectedCols = ['id', 'title', 'avatar', 'free', 'level', 'views', 'duration', 'originSrc'];

    /**
     * Display a listing of the resource.
     *
     * @param $count : 每一页展现的内容数
     * @param $page : 页数
     * @return \Illuminate\Http\Response
     */
    public function index($level, $count, $page)
    {
        if(!in_array($level, ['beginner', 'intermediate', 'advanced', 'all'])) {
           $level = 'all';
        }

        if($level == 'all') {
            $builder = Video::latest();
        } else {
            $builder = Video::latest()->where('level', $level);
        }
        $videos = $builder->paginate($count, $this->selectedCols, 'page', $page);

        return $this->response->paginator($videos, new VideoTransformer());
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
        $video = Video::find($id, ['id', 'title', 'likes', 'views', 'free', 'avatar', 'duration', 'parsed_desc', 'content', 'parsed_content', 'parsed_content_zh', 'points', 'originSrc']);
        if(!$video){
            $this->response->errorNotFound();
        }

        $result = [
            'id' => $video->id,
            'title' => $video->title,
            'likes' => $video->likes,
            'views' => $video->views,
            'free' => $video->free,
            'avatar' => $video->avatar,
            'duration' => $video->duration,
            'youtubeId' => $video->originSrc,
        ];

        $payContent = [
            'parsed_desc' => $video->parsed_desc,
            'parsed_content' => $video->parsed_content,
            'parsed_content_zh' => $video->parsed_content_zh,
            'points' => $video->points,
        ];

        if($video->free) {
            return array_merge($result, $payContent);
        }

        $token = Input::get('token');

        if($token) {
            $user = JWTAuth::toUser($token);
            if ($user->can('videos.subs')) {
                return array_merge($result,$payContent);
            }
        }

        return $result;
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
