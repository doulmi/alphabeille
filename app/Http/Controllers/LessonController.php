<?php

namespace App\Http\Controllers;

use App\Lesson;
use App\Topic;
use App\Collectable;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;

class LessonController extends ReadableController
{
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param int $lan : fr, zh_CN
     * @return \Illuminate\Http\Response
     */
    public function show($idOrSlug, $lan = 'fr')
    {
        $lesson = Lesson::findByIdOrSlugOrFail($idOrSlug);

        if(!$lesson) {

        }
        $topicController = new TopicController();
        $id = $lesson->id;
        Redis::incr('lesson:view:' . $id);

        $topic = $lesson->topic;
        Redis::incr('topic:view:' . $topic->id);

//        $topics = $topicController->random();
        $lessons = $this->random(4);

        if ($lan == 'fr') {
            $content = $lesson->parsed_content;
        } else if ($lan == 'zh_CN') {
            $lesson->audio_url = $lesson->audio_url_zh_CN;
            $content = $lesson->parsed_content_zh_CN;
        } else {
            abort(404);
        }

        list($like, $collect, $punchin) = $this->getStatus($lesson);

        $readable = $lesson;
        $type = 'lesson';
        return view('lessons.show', compact(['readable', 'type', 'topic', 'id', 'lessons', 'content', 'like', 'collect', 'punchin']));
    }
}
