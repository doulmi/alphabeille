<?php

namespace App;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class Talkshow extends Readable
{
    protected $dates = ['publish_at'];
    protected $fillable = [
        'title', 'description', 'avatar', 'likes', 'views', 'avatar', 'free', 'audio_url', 'download_url', 'duration', 'audio_url_zh_CN', 'content', 'content_zh_CN', 'keywords', 'is_published', 'publish_at','slug', 'parsed_content'
    ];

    public static function findByIdOrSlugOrFail($idOrSlug)
    {
        if (is_numeric($idOrSlug)) {
            $entity = Talkshow::find($idOrSlug);
            if($entity) return $entity;
        }
        //如果不是数字，或者是数字也没找到
        $entity = Talkshow::where('slug', $idOrSlug)->first();
        if ($entity) {
            return $entity;
        } else {
            throw (new ModelNotFoundException)->setModel(Talkshow::class);
        }
    }
}
