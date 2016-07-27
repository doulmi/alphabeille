<?php

namespace App;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class Video extends Model
{
    use SoftDeletes;
    use Sluggable;

    protected $dates = ['publish_at'];
    protected $fillable = [
        'title', 'description', 'avatar', 'likes', 'views', 'avatar', 'free', 'download_url', 'video_url', 'content', 'parsed_content','keywords', 'is_published', 'publish_at','slug'
    ];

    public function isNew()
    {
        return (Carbon::now()->diffInDays($this->created_at)) < Config::get('topic_updated_days');
    }

    public function comments()
    {
        return $this->hasMany(VideoComment::class);
    }

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
            throw (new ModelNotFoundException)->setModel(get_class(Video::class));
        }
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
