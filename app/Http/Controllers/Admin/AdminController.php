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

    public function parseVideos($start, $end)
    {
        $videos = Video::where('id', '>=', $start)->where('id', '<=', $end)->get();
        foreach ($videos as $video) {
            list($video->parsed_content, $video->parsed_content_zh, $video->points) = Helper::parsePointLink(Helper::filterSpecialChars($video->content));
            $video->parsed_desc = $this->markdown->parse($video->description);

            $video->save();
        }

    }

    public function parseMinitalk()
    {
        $minitalks = Minitalk::all();
        foreach ($minitalks as $minitalk) {

            $minitalk->parsed_content = Helper::emberedWord($this->markdown->parse(Helper::filterSpecialChars($minitalk->content)));
            $minitalk->parsed_wechat_part = $this->markdown->parse($minitalk->wechat_part);
            $minitalk->save();
        }
    }

    public function uploadSql(Request $request)
    {
        $sql = $request->get('sql');

        $destinationPath = '/var/www/sql/';
        $filename = $destinationPath . 'videos-' . time() . '.sql';
        file_put_contents($filename, $sql);
    }

    public function changeDate()
    {
        $users = User::all();

        $faker = Factory::create();
        foreach ($users as $user) {
            if ($user->id >= 9) {
                $user->created_at = $faker->dateTimeBetween('-21 days', 'now');
                $user->save();
            }
        }
    }

    //重新解析所有Video的Description，并保存
    public function parseDesc()
    {
        $videos = Video::all();
        foreach ($videos as $video) {
            $video->parsed_desc = $this->markdown->parse($video->description);
            $video->update();
        }
    }

//
//    public function saveView() {
//        $videos = Video::all();
//        foreach ($videos as $video) {
//            $video->views = Redis::get('video:view:' . $video->id);
//        }
//    }

    public function updateViewsMinitalks($from = 0)
    {
        $faker = Factory::create();

        $minitalks = Minitalk::all();
        foreach ($minitalks as $minitalk) {
//            $old = Redis::get('minitalk:view:' . $minitalk->id);
//            Redis::set('minitalk:view:' . $minitalk->id, $faker->numberBetween(2, 10) + $old);
            $minitalk->update([
                'views' => $faker->numberBetween(50, 200)
            ]);
        }
    }

    //更新网站内容的观看次数
    public function updateViews($from = 0)
    {
        $faker = Factory::create();

//        $minitalks = Minitalk::all();
//        foreach($minitalks as $minitalk) {
//            $old = Redis::get('minitalk:view:' . $minitalk->id);
//            Redis::set('minitalk:view:' . $minitalk->id, $faker->numberBetween(2, 10) + $old);
//        }
//
//        $videos = Video::where('id', '>', $from)->get();
//        foreach($videos as $video) {
//            $old = Redis::get('video:view:' . $video->id);
//            Redis::set('video:view:' . $video->id, $faker->numberBetween(8, 20) + $old );
//        }

        $videos = Video::where('id', '>', $from)->get();
        foreach ($videos as $video) {
            switch($video->state) {
                case 1 :
                case 7:
                    $views = $faker->numberBetween(1, 10);
                    break;
                case 2 :
                case 3 :
                case 4 :
                    $views = $faker->numberBetween(10, 30);
                    break;
                case 5:
                case 6:
                    $views = $faker->numberBetween(30, 140);
            }
            $video->update([
                'views' => $views
            ]);
        }

        Video::where('id', 1)->update([
            'views' => 489
        ]);

        Video::where('id', 638)->update([
            'views' => 502
        ]);

        Video::where('id', 7)->update([
            'views' => 563
        ]);
    }

    public function updateAvatar() {

        $videos = Video::get();
        foreach($videos as $video) {
            $id = $video->originSrc;
            $video->update([
                'avatar' => "http://o9dnc9u2v.bkt.clouddn.com/videos/$id-1.jpg"
            ]);
        }
    }

    public function parse()
    {
        $talks = Minitalk::all();
        foreach ($talks as $talk) {
        }
    }
}
