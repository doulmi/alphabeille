<?php

namespace App\Http\Controllers\Admin;

use App\Commentable;
use App\Editor\Markdown\Markdown;
use App\Helper;
use App\Lesson;
use App\LessonComment;
use App\Minitalk;
use App\MinitalkComment;
use App\Readable;
use App\Talkshow;
use App\TalkshowComment;
use App\Topic;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Video;
use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class AdminController extends Controller
{
    private $markdown;
    /**
     * @param $makrdown
     */
    public function __construct(Markdown $markdown)
    {
        $this->markdown = $markdown;
    }
    public function index()
    {
        return view('admin.index');
    }

    public function saveParsedContent() {
//        $lessons = Lesson::all();
//        foreach($lessons as $lesson) {
//            $lesson->parsed_content = $this->markdown->parse($lesson->content);
//            $lesson->parsed_content_zh_CN = $this->markdown->parse($lesson->content_zh_CN);
//            $lesson->save();
//        }

        $minitalks = Minitalk::all();
        foreach($minitalks as $minitalk) {
            $minitalk->parsed_content = $this->markdown->parse($minitalk->content);
            $minitalk->parsed_wechat_part = $this->markdown->parse($minitalk->wechat_part);
            $minitalk->save();
        }

//        $talkshows = Talkshow::all();
//        foreach($talkshows as $talkshow) {
//            $talkshow->parsed_content = $this->markdown->parse($talkshow->content);
//            $talkshow->save();
//        }

        $videos = Video::all();
        foreach($videos as $video) {
            $video->parsed_content = Helper::parsePointLink($this->markdown->parse($video->content));
            $video->parsed_desc = Helper::parsePointLink($this->markdown->parse($video->description));
            $video->save();
        }
    }

    public function changeDate() {
        $users = User::all();

        $faker = Factory::create();
        foreach ($users as $user) {
            if($user->id >= 9) {
                $user->created_at = $faker->dateTimeBetween('-21 days', 'now');
                $user->save();
            }
        }
    }


    //重新解析所有Video的Description，并保存
    public function parseDesc() {
        $videos = Video::all();
        foreach($videos as $video) {
            $video->parsed_desc = $this->markdown->parse($video->description);
            $video->update();
        }
    }

    //更新网站内容的观看次数
    public function updateViews($from) {
        $faker = Factory::create();

        $minitalks = Minitalk::all();
        foreach($minitalks as $minitalk) {
            $old = Redis::get('minitalk:view:' . $minitalk->id);
            Redis::set('minitalk:view:' . $minitalk->id, $faker->numberBetween(40, 60) + $old);
        }

        $videos = Video::where('id', '>', $from)->get();
        foreach($videos as $video) {
            $old = Redis::get('video:view:' . $video->id);
            Redis::set('video:view:' . $video->id, $faker->numberBetween(80, 120) + $old );
        }
    }

    public function readables() {
        $entity = Lesson::find(1);
        $readable = Readable::create([
            'title' => $entity->title,
            'description' => $entity->description,
            'avatar' => $entity->avatar,
            'free' => $entity->free,
            'audio_url' => $entity->audio_url,
            'download_url' => $entity->audio_url,
            'duration' => $entity->duration,
            'content' => $entity->content,
            'keywords' => $entity->keywords,
            'is_published' => $entity->is_published,
            'publish_at' => $entity->publish_at,
            'created_at' => $entity->created_at,
            'updated_at' => $entity->updated_at
        ]);
//        $lessons = Lesson::all();
//        foreach($lessons as $lesson) {
//            Readable::create($lesson);
//        }
//
//        $topics = Topic::all();
//        foreach($topics as $topic) {
//            $topic->save();
//        }
//
//        $minitalks = Minitalk::all();
//        foreach($minitalks as $minitalk) {
//            $minitalk->save();
//        }
//
//        $talkshows = Talkshow::all();
//        foreach($talkshows as $talkshow) {
//            $talkshow->save();
//        }
    }

    public function slugs() {
        $lessons = Lesson::all();
        foreach($lessons as $lesson) {
            $lesson->save();
        }

        $topics = Topic::all();
        foreach($topics as $topic) {
            $topic->save();
        }

        $minitalks = Minitalk::all();
        foreach($minitalks as $minitalk) {
            $minitalk->save();
        }

        $talkshows = Talkshow::all();
        foreach($talkshows as $talkshow) {
            $talkshow->save();
        }
    }

    public function generateDict() {
        Helper::generateDict();
    }

    public function videoLevels() {
        $videos = Video::all();
        foreach($videos as $video) {
            $video->update([
                'level' => 'beginner'
            ]);
        }
    }

    public function tmchange() {
        $talkshows = Talkshow::all();
        foreach($talkshows as $talkshow) {
            Minitalk::create([
                'title' => $talkshow->title,
                'description' => $talkshow->description,
                'avatar' => $talkshow->avatar,
                'audio_url' => $talkshow->audio_url,
                'download_url' => $talkshow->audio_url,
                'content' => $talkshow->content,
                'keywords' => $talkshow->keywords,
                'wechat_part' => '',
                'is_published' => 1,
                'publish_at' => $talkshow->publish_at,
                'likes' => 0,
                'free' => 0,
                'views' => 0,
                'created_at' => $talkshow->created_at,
                'updated_at' => $talkshow->updated_at,
                'deleted_at' => $talkshow->deleted_at,
                'slug' => $talkshow->slug,
                'parsed_content' => $talkshow->parsed_content,
                'parsed_wechat_part' => '',
                'points' => ''
            ]);
        }
    }
}
