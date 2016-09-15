<?php

namespace App\Http\Controllers\Admin;

use App\ActionType;
use App\Editor\Markdown\Markdown;
use App\Helper;
use App\Lesson;
use App\Minitalk;
use App\Task;
use App\Topic;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\UserTraces;
use App\Video;
use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $today = date('Y-m-d') . ' 00:00:00';
        /*视频数据*/
        //今天新增的视频数量
        $todayNewVideosNum = Video::where('created_at', '>=', $today)->count();
        //所有的视频数量
        $videosNum = Video::count();

        //今日提交的翻译视频个数
        $todayTranslateNum = UserTraces::where('action', ActionType::$submitTranslate)->where('created_at', '>=', $today)->count();
        //所有提交的翻译视频视频个数
        $translateNum = UserTraces::where('action', ActionType::$submitTranslate)->count();

        //今天提交的法语校对视频个数
        $todayCheckFrNum = UserTraces::where('action', ActionType::$submitCheckFr)->where('created_at', '>=', $today)->count();
        //所有已校对视频个数
        $checkFrNum = UserTraces::where('action', ActionType::$submitCheckFr)->count();

        //今天确认的法语翻译
        $todayValidNum = UserTraces::where('action', ActionType::$validTranslate)->where('created_at', '>=', $today)->count();
        //所有确认的法语翻译
        $validNum = UserTraces::where('action', ActionType::$validTranslate)->count();

        //所有已完成视频
        $publishedVideoNum = Video::where('state', 6)->count();
        $translatedVideoNum = Video::where('state',5)->count();

        //今天播放量
        $todayVideoViews = UserTraces::where('action', ActionType::$view)->where('readable_type', 'App\Video')->where('created_at', '>=', $today)->count();

        //所有播放量
        $videoViews = UserTraces::where('action', ActionType::$view)->where('readable_type', 'App\Video')->count();

        /*用户数据*/
        //今日新用户
        $todayUserNum = User::where('created_at', '>=', $today)->count();
        //所有用户
        $userNum = User::count();

        //今天新增VIP
        $todayVipNum = DB::table('role_user')->where('role_id', 4)->where('updated_at', '>=', $today)->count();
        //所有VIP
        $vipNum = DB::table('role_user')->where('role_id', 4)->count();

        $translatorsNum = DB::table('role_user')->where('role_id', 5)->count();
        return view('admin.statistics', compact('todayNewVideosNum', 'videosNum', 'todayTranslateNum', 'translateNum', 'todayCheckFrNum', 'checkFrNum', 'todayValidNum', 'validNum', 'publishedVideoNum', 'todayVideoViews', 'videoViews', 'todayVipNum', 'vipNum', 'todayUserNum', 'userNum', 'translatorsNum', 'translatedVideoNum'));
    }

    public function task2Trace()
    {
        $tasks = Task::where('type', 2)->where('is_submit', 1)->lists('id')->toArray();
        $videos = Video::whereIn('id', $tasks)->whereIn('state', [4])->get();
        foreach ($videos as $video) {
            $trace = UserTraces::where('readable_id', $video->id)->where('user_id', 3)->where('action', ActionType::$validTranslate)->first();
            if (!$trace) {
                UserTraces::create([
                    'readable_type' => 'App\Video',
                    'readable_id' => $video->id,
                    'user_id' => 3,
                    'action' => ActionType::$validTranslate,
                    'created_at' => $video->updated_at
                ]);
            }
        }
//        foreach ($tasks as $task) {
//            if ($task->type == 1) {//checkFr
//                echo $task->id . ' checkFr<br>';
//                UserTraces::create([
//                    'readable_type' => 'App\Video',
//                    'readable_id' => $task->video_id,
//                    'user_id' => $task->user_id,
//                    'action' => ActionType::$checkFr,
//                    'created_at' => $task->created_at
//                ]);
//                if ($task->is_submit) {
//                    UserTraces::create([
//                        'readable_type' => 'App\Video',
//                        'readable_id' => $task->video_id,
//                        'user_id' => $task->user_id,
//                        'action' => ActionType::$submitCheckFr,
//                        'created_at' => $task->updated_at
//                    ]);
//                    echo $task->id . ' subcheckFr<br>';
//                }
//            } else {    //type = 2, tranlate
//                UserTraces::create([
//                    'readable_type' => 'App\Video',
//                    'readable_id' => $task->video_id,
//                    'user_id' => $task->user_id,
//                    'action' => ActionType::$translate,
//                    'created_at' => $task->created_at
//                ]);
//                echo $task->id . ' traskalte<br/>';
//                if ($task->is_submit) {
//                    UserTraces::create([
//                        'readable_type' => 'App\Video',
//                        'readable_id' => $task->video_id,
//                        'user_id' => $task->user_id,
//                        'action' => ActionType::$submitTranslate,
//                        'created_at' => $task->updated_at
//                    ]);
//                    echo $task->id . ' subtraskalte<br>';
//                }
//            }
//        }
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
            switch ($video->state) {
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

    public function updateAvatar()
    {
        $videos = Video::get();
        foreach ($videos as $video) {
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

    public function addMins()
    {
        $users = User::get();
        foreach ($users as $user) {
            $videos = $user->learnedVideos()->get(['id', 'duration']);
            $mins = 0;
            foreach ($videos as $video) {
                $mins += Helper::str2Min($video->duration);
            }

            $minitalks = $user->learnedMinitalks()->get(['duration']);
            foreach ($minitalks as $minitalk) {
                $mins += Helper::str2Min($minitalk->duration);
            }

            $user->mins = $mins;
            $user->update();
        }
    }
}
