<?php

namespace App;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Nicolaslopezj\Searchable\SearchableTrait;

class Video extends Readable
{
    const WAIT_LISTEN = 0;
    const WAIT_CHECK_FR = 1;
    const WAIT_TRANSLATE = 2;
    const ON_TRANSLATE = 3;
    const WAIT_CHECK_ZH = 4;
    const OK = 5;
    const PUBLISHED = 6;
    const ON_CHECK_FR = 7;
    const ON_CHECK_ZH = 8;

    protected $fillable = [
        'id', 'title', 'description', 'likes', 'views', 'avatar', 'free', 'download_url', 'video_url', 'content', 'parsed_content','keywords', 'is_published', 'publish_at','slug', 'parsed_content_zh', 'points', 'level', 'translator_id', 'listener_id', 'verifier_id', 'lastMonthViews', 'originSrc', 'parsed_desc', 'state', 'duration'
    ];

    use SearchableTrait;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'title' => 10,
            'description' => 5,
            'content' => 2
        ]
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
