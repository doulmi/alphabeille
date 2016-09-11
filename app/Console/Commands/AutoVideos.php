<?php

namespace App\Console\Commands;

use App\Editor\Markdown\Markdown;
use App\Helper;
use App\User;
use App\Video;
use Carbon\Carbon;
use Illuminate\Console\Command;
use RecursiveDirectoryIterator;

class AutoVideos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'videos {path} {i=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate videos sql by youtubeIds';
    private $markdown;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Markdown $markdown)
    {
        parent::__construct();
        $this->markdown = $markdown;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //1. get ids from path
        $ids = $this->getYoutubeIds($this->argument('path'));
        $count = count($ids);
        $start = $this->argument('i');
        foreach ($ids as $i => $id) {
            if($i + 1 < $start) {
                continue;
            }
            echo ($i + 1) ."/$count - $id : ";
            if($this->oneSql($id)) {
                echo "success\n";
            } else {
                echo "failed\n";
            }
        }
    }

    /**
     * 取得该Youtube视频的长度
     * @param $id
     * @param $result
     * @return bool
     */
    function getDuration($id)
    {
        $root = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=contentDetails&id=$id&key=AIzaSyAWbKR4NMTQ1OrQ5WBaPKbkqlEv7x1rooU");
        $find = preg_match('/T(\d*)M(\d*)S/', $root, $data);

        if ($find) {
            $min = intval($data[1]);
            $sec = intval($data[2]);
            if ($min < 10) {
                $min = '0' . $min;
            }
            if ($sec < 10) {
                $sec = '0' . $sec;
            }
            $duration = $min . ':' . $sec;
            return $duration;
        } else {
            $find = preg_match('/T(\d*)S/', $root, $data);
            if ($find) {
                $sec = intval($data[1]);
                if ($sec < 10) {
                    $sec = '0' . $sec;
                }
                $duration = '00:' . $sec;
                return $duration;
            } else {
                $find = preg_match('/T(\d*)M/', $root, $data);
                if ($find) {
                    $min = intval($data[1]);
                    if ($min < 10) {
                        $min = '0' . $min;
                    }
                    $duration = $min . ":00";
                    return $duration;
                }
            }
        }

        return false;
    }


    private function oneSql($id)
    {
        $root = file_get_contents("https://www.youtube.com/watch?v=" . $id);
        $find = preg_match('/<meta name="title" content="(.*)">/', $root, $data);
        $duration = preg_match('/<span class="video-time">([\d|:]+)<\/span>/', $root, $d);
        if ($find) {
            $title = html_entity_decode($data[1]);
            $findKeys = preg_match('/<meta name="keywords" content="(.*)">/', $root, $keys);
            if($findKeys) {
                $requestData['keywords'] = $keys[1];
            } else {
                $requestData['keywords'] = str_slug($title, ', ');
            }
            $requestData = [];
            $requestData['originSrc'] = $id;
            $requestData['title'] = $title;
            $requestData['avatar'] = "http://o9dnc9u2v.bkt.clouddn.com/videos/$id-1.jpg";
            $requestData['video_url'] = "http://o9dnc9u2v.bkt.clouddn.com/videos/$id.mp4";
            $requestData['download_url'] = "http://o9dnc9u2v.bkt.clouddn.com/videos/$id.mp4";
            $requestData['free'] = 0;
            $requestData['level'] = 'intermediate';
            $requestData['description'] = '';
            $requestData['duration'] = $this->getDuration($id);

            if($duration) {
                $requestData['duration'] = $d[1];
            }

            $content = $this->getContent($id);
            if(!$content) {
                return false;
            }
            $requestData['content'] = $content;
            $this->store($requestData);
            return true;
        } else {
            echo "Can't find title: " . $id;
            return false;
        }
    }

    /**
     * 读取对于的字幕文件，写到content中
     * @param $id
     * @return bool|string
     */
    private function getContent($id)
    {
        $dir = new RecursiveDirectoryIterator($this->argument('path'));
        for (; $dir->valid(); $dir->next()) {
            $name = $dir->getPathName();
            if (strpos($name, $id . '.srt')) {
                return $content = file_get_contents($name);
            }
        }
        echo "can not find content : " . $id;
        return false;
    }

    public function store($requestData)
    {
        $data = $this->getSaveData($requestData);
        $data['slug'] = '';
        $video = Video::create($data);
    }

    /**
     * 取得该文件夹下所有视频的YoutubeId
     * @param $path
     * @return array
     */
    private function getYoutubeIds($path)
    {
        $dir = new RecursiveDirectoryIterator($path);
        $ids = [];
        for (; $dir->valid(); $dir->next()) {
            $name = $dir->getPathName();
            if (strpos($name, 'mp4')) {
                $ids[] = str_replace('.mp4', '', str_replace($path . '\\', '', $name));
            }
        }
        return $ids;
    }


    private function getSaveData($requestData)
    {
        $data = $requestData;

        $data['content'] = Helper::filterSpecialChars($data['content']);

        list($data['parsed_content'], $data['parsed_content_zh'], $data['points']) = Helper::parsePointLink($data['content']);
        $data['parsed_desc'] = $this->getParsedDesc($data['description']);
        $data['publish_at'] = Carbon::now('Europe/Paris');


        return $data;
    }

    private function getParsedDesc($desc)
    {
        $parsedDesc = $this->markdown->parse($desc);
        return $parsedDesc;
    }
}
