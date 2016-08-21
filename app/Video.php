<?php

namespace App;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class Video extends Readable
{
    protected $fillable = [
        'id', 'title', 'description', 'likes', 'views', 'avatar', 'free', 'download_url', 'video_url', 'content', 'parsed_content','keywords', 'is_published', 'publish_at','slug', 'parsed_content_zh', 'points', 'level', 'translator_id', 'listener_id', 'verifier_id', 'lastMonthViews', 'originSrc', 'parsed_desc', 'state'
    ];

    public static function findByIdOrSlugOrFail($idOrSlug)
    {
        if (is_numeric($idOrSlug)) {
            $entity = Video::with(['translator', 'listener', 'verifier'])->find($idOrSlug);
            if($entity) return $entity;
        }
        //如果不是数字，或者是数字也没找到
        $entity = Video::with(['translator', 'listener', 'verifier'])->where('slug', $idOrSlug)->first();
        if ($entity) {
            return $entity;
        } else {
            throw (new ModelNotFoundException)->setModel(Video::class);
        }
    }

    public function translator() {
        return $this->hasOne(User::class, 'id', 'translator_id');
    }

    public function listener() {
        return $this->hasOne(User::class, 'id', 'listener_id');
    }

    public function verifier() {
        return $this->hasOne(User::class, 'id', 'verifier_id');
    }
}
