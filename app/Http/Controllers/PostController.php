<?php

namespace App\Http\Controllers;

use App\Http\Controllers\TalkshowController;
use App\Http\Controllers\TopicController;
use App\Subscription;
use Illuminate\Http\Request;
use EndaEditor;

use App\Http\Requests;

class PostController extends Controller
{
    public function index() {
        $topicController = new TopicController();
        $topics = $topicController->latest(8);

        $talkshowController = new TalkshowController();
        $talkshows = $talkshowController->latest(4);

        $menus = Subscription::all();
        return view('index', compact(['topics', 'talkshows', 'menus']));
    }

    public function menus() {
        $menus = Subscription::all();
        return view('menus', compact('menus'));
    }

    public function upload() {
        $data = EndaEditor::uploadImgFile('uploads');
        return json_encode($data);
    }
}
