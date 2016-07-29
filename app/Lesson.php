<?php

namespace App;

use Carbon\Carbon;
use Config;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Readable
{
    protected $fillable = [
        'topic_id', 'title', 'order', 'views', 'likes', 'description', 'free', 'audio_url', 'download_url', 'duration', 'audio_url_zh_CN', 'content', 'content_zh_CN', 'avatar', 'keywords', 'is_published', 'publish_at', 'slug', 'parsed_content', 'parsed_content_zh_CN'
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public static function findByIdOrSlugOrFail($idOrSlug)
    {
        if (is_numeric($idOrSlug)) {
            $entity = Lesson::find($idOrSlug);
            if($entity) return $entity;
        }
        //如果不是数字，或者是数字也没有找到
        $lesson = Lesson::where('slug', $idOrSlug)->first();
        if ($lesson) {
            return $lesson;
        } else {
            throw (new ModelNotFoundException)->setModel(Lesson::class);
        }
    }
}
