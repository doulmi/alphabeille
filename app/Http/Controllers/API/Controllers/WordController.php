<?php

namespace App\Http\Controllers\Api\Controllers;

use App\Http\Controllers\Api\Transformers\VideoTransformer;
use App\Video;
use App\Word;
use Illuminate\Http\Request;
use JWTAuth;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class WordController extends BaseApiController
{
    public function show($word, $videoId)
    {
//        $video = Video::find($id, ['id', 'title', 'state', 'level', 'likes', 'views', 'free', 'avatar', 'duration', 'parsed_desc', 'parsed_content', 'parsed_content_zh', 'points', 'originSrc']);
        $controller = new \App\Http\Controllers\WordController();
        return $controller->show($word, $videoId, 'App\Video');
    }
}
