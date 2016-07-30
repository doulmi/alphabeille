<?php

namespace App;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class Minitalk extends Readable
{
    protected $fillable = [
        'title', 'description', 'avatar', 'likes', 'views', 'avatar', 'audio_url', 'download_url', 'keywords', 'is_published', 'publish_at', 'free', 'wechat_part', 'content', 'slug', 'parsed_content', 'parsed_wechat_part'
    ];

    public static function findByIdOrSlugOrFail($idOrSlug)
    {
        if (is_numeric($idOrSlug)) {
            $entity = Minitalk::find($idOrSlug);
            if($entity) return $entity;
        }
        //如果不是数字，或者是数字也没有找到
        $entity = Minitalk::where('slug', $idOrSlug)->first();
        if ($entity) {
            return $entity;
        } else {
            throw (new ModelNotFoundException)->setModel(Minitalk::class);
        }
    }
}
