<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Lesson;
use App\Minitalk;
use App\Subscription;
use App\Talkshow;

use App\Http\Requests;
use App\Video;
use App\Word;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use TomLingham\Searchy\Facades\Searchy;

class PostController extends Controller
{
    public function dict()
    {
        for ($i = 98; $i < 97 + 24; $i++) {
            Word::create([
                'word' => chr($i),
                'explication' => chr($i) . ', ' . chr($i - 32) . '是拉丁字母中的第' . $i . '个字母。'
            ]);
        }

        $i = 97 + 25;

        Word::create([
            'word' => chr($i),
            'explication' => chr($i) . ', ' . chr($i - 32) . '是拉丁字母中的第' . $i . '个字母。'
        ]);

        $minitalks = Minitalk::all();
        foreach($minitalks as $minitalk) {
//            $parsed_content = str_replace('‘', '\'', $minitalk->parsed_content);
//            $parsed_content = str_replace('’', '\'', $parsed_content);
//            $parsed_content = str_replace('《', '«', $parsed_content);
//            $parsed_content = str_replace('》', '»', $parsed_content);
//            $parsed_content = str_replace('  ', ' ', $parsed_content);
//            $parsed_content = str_replace('，', ',', $parsed_content);
//            $parsed_content = str_replace('。', '.', $parsed_content);
//            $minitalk->parsed_content = $parsed_content;
//
//            $minitalk->parsed_content = Helper::emberedWord($minitalk->parsed_content);
            $minitalk->publish_at = date('Y-m-d h:i:s');
            $minitalk->save();
//            dd($minitalk);
        }

        $lessons = Lesson::all();
        foreach($lessons as $lesson) {
//            $parsed_content = str_replace('‘', '\'', $lesson->parsed_content);
//            $parsed_content = str_replace('’', '\'', $parsed_content);
//            $parsed_content = str_replace('《', '«', $parsed_content);
//            $parsed_content = str_replace('》', '»', $parsed_content);
//            $parsed_content = str_replace('  ', ' ', $parsed_content);
//            $parsed_content = str_replace('，', ',', $parsed_content);
//            $parsed_content = str_replace('。', '.', $parsed_content);
//            $lesson->parsed_content = $parsed_content;
//
//            $lesson->parsed_content = Helper::emberedWord($lesson->parsed_content);
            $lesson->publish_at = date('Y-m-d h:i:s');
            $lesson->save();
        }
    }

    public function parse() {
        $minitalks = Minitalk::all();
        foreach($minitalks as $minitalk) {
            $parsed_content = str_replace('‘', '\'', $minitalk->parsed_content);
            $parsed_content = str_replace('’', '\'', $parsed_content);
            $parsed_content = str_replace('《', '«', $parsed_content);
            $parsed_content = str_replace('》', '»', $parsed_content);
            $parsed_content = str_replace('  ', ' ', $parsed_content);
            $parsed_content = str_replace('，', ',', $parsed_content);
            $parsed_content = str_replace('。', '.', $parsed_content);
            $minitalk->parsed_content = $parsed_content;

            $minitalk->parsed_content = Helper::emberedWord($minitalk->parsed_content);
            $minitalk->save();
        }

        $lessons = Lesson::all();
        foreach($lessons as $lesson) {
            $parsed_content = str_replace('‘', '\'', $lesson->parsed_content);
            $parsed_content = str_replace('’', '\'', $parsed_content);
            $parsed_content = str_replace('《', '«', $parsed_content);
            $parsed_content = str_replace('》', '»', $parsed_content);
            $parsed_content = str_replace('  ', ' ', $parsed_content);
            $parsed_content = str_replace('，', ',', $parsed_content);
            $parsed_content = str_replace('。', '.', $parsed_content);
            $lesson->parsed_content = $parsed_content;

            $lesson->parsed_content = Helper::emberedWord($lesson->parsed_content);
            $lesson->save();
        }
    }
    public function test()
    {
//
//        $str ='hello World陈锋誉is啊googd  bo男';
//        for ( $i = 0; $i < mb_strlen($str,'utf8'); $i ++) {
//            $char = mb_substr($str, $i, 1, 'utf-8');
//            var_dump($char . " : " . $this->isChineseChar($char));
//        };
        $lesson = Minitalk::find(11);
        $parsed_content = str_replace('‘', '\'', $lesson->parsed_content);
        $parsed_content = str_replace('’', '\'', $parsed_content);
        $parsed_content = str_replace('《', '«', $parsed_content);
        $parsed_content = str_replace('》', '»', $parsed_content);
        $parsed_content = str_replace('  ', ' ', $parsed_content);
        $parsed_content = str_replace('，', ',', $parsed_content);
        $parsed_content = str_replace('。', '.', $parsed_content);
        $lesson->parsed_content = $parsed_content;

//        dd($lesson->parsed_content);
        $lesson->parsed_content = Helper::emberedWord($lesson->parsed_content);
        $lesson->save();
    }

    public function index()
    {
        $num = Config::get('params')['indexPageLimit'];
        $lessons = Lesson::published()->orderBy('free', 'DESC')->orderBy('id', 'DESC')->limit($num)->get();

        $talkshows = Talkshow::published()->orderBy('free', 'DESC')->latest()->limit($num)->get();
        $minitalks = Minitalk::published()->orderBy('free', 'DESC')->latest()->limit($num)->get();
        $videos = Video::published()->orderBy('free', 'DESC')->latest()->limit($num)->get();
        return view('index', compact(['lessons', 'talkshows', 'minitalks', 'videos']));
    }

    public function menus()
    {
        $menus = Subscription::all();
        return view('menus', compact('menus'));
    }

//    public function upload()
//    {
//        $data = EndaEditor::uploadImgFile('uploads');
//        return json_encode($data);
//    }

    public function search()
    {
        $keys = Input::get('keys', '');
        $talkshows = Searchy::talkshows(['title', 'content', 'content_zh_CN'])->query($keys)->get();
        $lessons = Searchy::lessons(['title', 'content', 'content_zh_CN'])->query($keys)->get();
        $discussions = Searchy::discussions(['title', 'content'])->query($keys)->get();
        $minitalks = Searchy::minitalks(['title', 'content'])->query($keys)->get();
        return view('search', compact(['talkshows', 'lessons', 'discussions', 'minitalks']));
    }

    public function free()
    {
        $talkshows = Talkshow::where('free', 1)->get();
        $lessons = Lesson::where('free', 1)->get();
        $minitalks = Minitalk::where('free', 1)->get();
        return view('free', compact(['talkshows', 'lessons', 'minitalks']));
    }

    public function subscription($id)
    {
        $menu = Subscription::findOrFail($id);
        return view('payment', compact('menu'));
    }

    public function basicCourses()
    {
        return view('cours/basicCourses');
    }

    public function oralFormation()
    {
        return view('cours/oralFormation');
    }

    public function privateCourses()
    {
        return view('cours/privateCourses');
    }
}
