<?php

namespace App;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class Talkshow extends Model
{
    use SoftDeletes;
    use Sluggable;

    protected $dates = ['publish_at'];
    protected $fillable = [
        'title', 'description', 'avatar', 'likes', 'views', 'avatar', 'free', 'audio_url', 'download_url', 'duration',  'audio_url_zh_CN', 'content', 'content_zh_CN', 'keywords', 'is_published', 'publish_at'
    ];

    public function isNew()
    {
        return (Carbon::now()->diffInDays($this->created_at)) < Config::get('topic_updated_days');
    }

    public function comments() {
        return $this->hasMany(TalkshowComment::class);
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
