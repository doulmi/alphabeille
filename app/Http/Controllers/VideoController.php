<?php

namespace App\Http\Controllers;

use App\Editor\Markdown\Markdown;
use App\Helper;
use App\Note;
use App\UserTraces;
use App\Video;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;

class VideoController extends ReadableController
{
    private $markdown;

    /**
     * @param $makrdown
     */
    public function __construct(Markdown $markdown)
    {
        $this->markdown = $markdown;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $idOrSlug)
    {
        $readable = Video::findByIdOrSlugOrFail($idOrSlug);

        $readable->increment('views');

        $readables = $this->random();
        $user = Auth::user();

        if ($user) {
            UserTraces::create([
                'user_id' => $user->id,
                'readable_type' => 'App\Video',
                'readable_id' => $readable->id
            ]);
            $user->update([
                'mins' => Helper::str2Min($readable->duration) + $user->mins
            ]);
            $youtube = $user->last_login_foreign;
        } else {
            $youtube = Helper::isForeignIp($request->ip());
        }

        $fr = explode('||', $readable->parsed_content);
        $zh = explode('||', $readable->parsed_content_zh);

        list($like, $collect, $punchin) = $this->getStatus($readable);

        $type = 'video';

        //notes
        $notes = Note::where('user_id', Auth::id())->where('readable_id', $readable->id)->where('readable_type', 'App\Video')->get(['point', 'content', 'id'])->toJson();
        $notes = str_replace("'", "\'", $notes);

        return view('videos.show', compact(['readables', 'type', 'readable', 'fr', 'zh', 'like', 'collect', 'punchin', 'youtube','notes']));
    }

    public function level($level)
    {
        $pageLimit = config('params')['pageLimit'];
        $readables = Video::where('level', $level)->published()->orderBy('free', 'DESC')->orderBy('state', 'DESC')->latest()->paginate($pageLimit, ['id', 'avatar', 'title', 'slug', 'created_at','level', 'free', 'state', 'views']);
        $type = 'video';
        return view('videos.index', compact(['readables', 'type']));
    }

    public function index(Request $request) {
        $pageLimit = config('params')['pageLimit'];
        $orderBy = $request->get('orderBy', 'views');
        $cols = ['id', 'avatar', 'title', 'slug', 'created_at','level', 'free', 'state', 'views'];
        $builder = Video::orderBy('free', 'DESC');//->orderBy('state', 'DESC');

        if($orderBy == 'latest') {
            $builder->orderBy('created_at', 'DESC');
        } else {
            $builder->orderBy('views', 'DESC');
        }

        $readables = $builder->paginate($pageLimit, $cols)->appends($request->all());
        $type = 'video';
        return view('videos.index', compact(['readables', 'type']));
    }

    public function updateNote(Request $request) {
        $id = $request->get('id');
        $content = $request->get('content');

        Note::where('id', $id)->where('user_id', Auth::id())->update([
            'content' => $content
        ]);

        return response()->json([
            'status' => 200
        ]);
    }

    public function deleteNote(Request $request) {
        $id = $request->get('noteId');
        Note::where('id', $id)->where('user_id', Auth::id())->delete();

        return response()->json([
            'status' => 200
        ]);
    }
}
