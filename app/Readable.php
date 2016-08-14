<?php

namespace App;

use Carbon\Carbon;
use Config;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Readable extends Model {
    use SoftDeletes;
    use Sluggable;

    protected $dates = ['publish_at'];

    protected $fillable = [
        'title', 'description', 'avatar', 'likes', 'views', 'free', 'audio_url', 'download_url', 'duration', 'content',  'keywords', 'is_published', 'publish_at', 'created_at', 'updated_at'
    ];

    public function isNew()
    {
        return (Carbon::now()->diffInDays($this->created_at)) < Config::get('params')['updatedDays'];
    }

    public function comments()
    {
        return $this->morphMany('App\Commentable', 'commentable');
    }

    public function collects()
    {
        return $this->morphMany('App\Collectable', 'collectable');
    }

    public function likes() {
        return $this->morphMany('App\Likeable', 'likeable');
    }

    public function scopePublished($query)
    {
        return $query->whereDate('publish_at', '<=', Carbon::now("Europe/Paris")->toDateString());
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
