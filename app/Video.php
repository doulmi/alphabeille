<?php

namespace App;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class Video extends Readable
{
    protected $fillable = [
        'title', 'description', 'likes', 'views', 'avatar', 'free', 'download_url', 'video_url', 'content', 'parsed_content','keywords', 'is_published', 'publish_at','slug', 'parsed_content_zh', 'points', 'level', 'translator_id', 'listener_id', 'verifier_id', 'lastMonthViews', 'originSrc'
    ];

    public static function findByIdOrSlugOrFail($idOrSlug)
    {
        if (is_numeric($idOrSlug)) {
            $entity = Video::find($idOrSlug);
            if($entity) return $entity;
        }
        //如果不是数字，或者是数字也没找到
        $entity = Video::where('slug', $idOrSlug)->first();
        if ($entity) {
            return $entity;
        } else {
            throw (new ModelNotFoundException)->setModel(Video::class);
        }
    }

    public function translator() {
        return User::where('id', $this->translator_id)->first();
    }

    public function listener() {
        return User::where('id', $this->listener_id)->first();
    }

    public function verifier() {
        return User::where('id', $this->verifier_id)->first();
    }
}
