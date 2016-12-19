<?php

namespace App\Http\Controllers\Api\Transformers;

use App\Video;
use League\Fractal\TransformerAbstract;

class VideoTransformer extends TransformerAbstract
{
  public function transform(Video $video)
  {
    return [
      'id' => $video->id,
      'title' => $video->title,
      'likes' => $video->likes,
      'views' => $video->views,
      'free' => $video->free,
      'avatar' => $video->avatar,
      'slug' => $video->slug,
      'duration' => $video->duration,
      'youtubeId' => $video->originSrc,
      'state' => $video->state,
      'level' => $video->level,
    ];
  }
}