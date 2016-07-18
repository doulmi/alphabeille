<?php

namespace App;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class Topic extends Model
{
    use SoftDeletes;
    use Sluggable;

    protected $dates = ['publish_at'];

    protected $fillable = [
        'title', 'description', 'avatar', 'level', 'keywords', 'is_published', 'publish_at'
    ];

    public function lessons() {
        return $this->hasMany(Lesson::class);
    }

    public function views() {
        return Lesson::where('topic_id', $this->id)->sum('views');
    }

    public function likes() {
        return Lesson::where('topic_id', $this->id)->sum('likes');
    }

    public function lessonCount() {
        return Lesson::where('topic_id', $this->id)->count();
    }

    public function getState() {
        $lesson = Lesson::where('topic_id', $this->id)->latest()->first();
        if($lesson != null) {
            $isUpdated = (Carbon::now()->diffInDays($lesson->created_at)) < Config::get('topic_updated_days');
            $isNew = (Carbon::now()->diffInDays($lesson->created_at)) < Config::get('topic_updated_days');
            return [
                'isUpdated' => $isUpdated,
                'isNew' => $isNew
            ];
        } else {
            return [
                'isUpdated' => false,
                'isNew' => false,
            ];
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
