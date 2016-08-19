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
    protected $signature = 'videos {path}';

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
        foreach ($ids as $i => $id) {
            if($this->oneSql($id)) {
                echo "$i/$count - $id : success\n";
            } else {
                echo "$i/$count - $id : failed\n";
            }
        }
    }

    private function oneSql($id)
    {
        $root = file_get_contents("https://www.youtube.com/watch?v=" . $id);
        $find = preg_match('/<meta name="title" content="(.*)">/', $root, $data);
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
            $requestData['avatar'] = "http://o9dnc9u2v.bkt.clouddn.com/videos/$id.jpg";
            $requestData['video_url'] = "http://o9dnc9u2v.bkt.clouddn.com/videos/$id.mp4";
            $requestData['download_url'] = "http://o9dnc9u2v.bkt.clouddn.com/videos/$id.mp4";
            $requestData['free'] = 0;
            $requestData['level'] = 'intermediate';
            $requestData['description'] = '';
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

        $content = str_replace('！', '!', $data['content']);
        $content = str_replace('？', '?', $content);
        $content = str_replace('  ', ' ', $content);
        $content = str_replace('‘', '\'', $content);
        $content = str_replace('’', '\'', $content);
        $content = str_replace('“', '\'', $content);
        $content = str_replace('”', '\'', $content);
        $content = str_replace('"', '\'', $content);
        $content = str_replace('。', '.', $content);
        $content = str_replace('，', ',', $content);
        $content = str_replace('…', '...', $content);
        $content = str_replace('!', '.', $content);
        $content = str_replace('\n\n', '\n', $content);
        $data['content'] = $content;

        list($data['parsed_content'], $data['parsed_content_zh'], $data['points']) = Helper::parsePointLink($content);
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
