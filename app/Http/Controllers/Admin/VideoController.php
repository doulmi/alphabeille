<?php

namespace App\Http\Controllers\Admin;

use App\Editor\Markdown\Markdown;
use App\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class VideoController extends Controller
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
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orderBy = Input::get('orderBy', 'created_at');
        $dir = Input::get('dir', 'DESC');
        $limit = Input::get('limit', 50);
        $search = Input::get('search', '');
        $state = Input::get('state', '');

        $builder = Video::orderBy($orderBy, $dir);
//        if($zh != 0) {
//            $builder->where('parsed_content_zh', '');
//        }
//        if ($desc != 0) {
//            $builder->where('parsed_desc', '');
//        }
        if($state != '') {
            $builder->where('state', $state);
        }
        if ($search != '') {
            $builder->where('title', 'like', "%$search%")->orWhere('originSrc', $search);
        }

        $videos = $builder->paginate($limit)->appends($request->all());;
        $levels = ['beginner', 'intermediate', 'advanced'];
        $states = ['0', '1', '2', '3', '4', '5', '6'];
        return view('admin.videos.index', compact(['videos', 'levels', 'states']));
    }

    public function changeLevel($videoId, $level) {
        $video = Video::find($videoId);
        if($video) {
            $video->level = $level;
            $video->save();
            return response()->json([
                'status' => 200
            ]);

        } else {
            return response()->json([
                'status' => 404
            ]);
        }
    }

    public function changeState($videoId, $state) {
        $video = Video::find($videoId);
        if($video) {
            $video->state = $state;
            $video->save();

            return response()->json([
                'status' => 200
            ]);

        } else {
            return response()->json([
                'status' => 404
            ]);
        }
    }

    public function extraFr($id) {
        $video = Video::find($id);
        echo(Helper::extraFr($video->content));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $edit = false;
        return view('admin.videos.show', compact(['edit']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->getSaveData($request);
        $data['slug'] = '';

        if ($data['preview'] == 1) {
            return $this->preview($data);
        } else {
            $video = Video::create($data);

            $user = Auth::user();
            if ($user) {
                Log::info($user->name . ' add video ' . $video->id);
            }
            Session::flash('success', trans('labels.createVideoSuccess'));
            return redirect('admin/videos');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $video = Video::firstOrFail($id);
        return $video;
    }

    public function preview($data)
    {
        $readable = new \stdClass();
        foreach ($data as $key => $t) {
            $readable->$key = $t;
        }
        $readable->desc = $readable->description;
        $readable->id = 1;
        return view('admin.videos.preview', compact('readable'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit = true;
        $video = Video::findOrFail($id);
        $time = $video->publish_at;
//        $isPm = $time->hour >= 12? true: false;

        $video->showTime = $time->day . '/' . $time->month . '/' . $time->year . ' ' . $time->hour . ':' . $time->minute;
        return view('admin.videos.show', compact(['edit', 'video']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->getSaveData($request);
        if ($data['preview'] == 1) {
            return $this->preview($data);
        } else {
            $video = Video::findOrFail($id);

            $video->update($data);

            $user = Auth::user();
            if ($user) {
                Log::info($user->name . ' update video ' . $id);
            }
            return redirect('admin/videos');
        }
    }
//
//    private function preview(Request $request) {
//
//    }

    public function unknownWords($id)
    {
        $video = Video::findOrFail($id);
        $words = Helper::getWordsNotInDict($video->content);
        return view('admin.unknown', compact('words'));
    }

    /**
     * 合并中法字幕文件到一个文件中
     */
    public function merge(Request $request)
    {
        $frSrc = $request->get('frSrc');
        $zhSrc = $request->get('zhSrc');
        $mergedSub = Helper::merge($frSrc, $zhSrc);
        return view('admin.videos.mergeResult', compact('mergedSub'));
    }

    public function showMerge()
    {
        return view('admin.videos.merge');
    }

    public function parse()
    {
        $videos = Video::all();
        foreach ($videos as $video) {
            if(trim(str_replace('||', '', $video->parsed_content_zh)) == '' ) {
                $video->parsed_content_zh = '';
            }

            $video->save();
        }
    }

    private function getSaveData(Request $request)
    {
        $data = $request->all();

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
        $content = str_replace(' ', ' ', $content);//特殊的空格,会被看做中文
        $content = str_replace('–', '-', $content);
        $content = str_replace('♪', '', $content);
        $data['content'] = $content;

        list($data['parsed_content'], $data['parsed_content_zh'], $data['points']) = Helper::parsePointLink($content);

        if (trim(str_replace('||', '', $data['parsed_content_zh']) == '')) {
            $data['parsed_content_zh'] = '';
        }
        $data['parsed_desc'] = $this->getParsedDesc($data['description']);

        if (isset($data['publish_at']) && $data['publish_at'] != '') {
            $times = explode(' ', $data['publish_at']);
            $times0 = explode('/', $times[0]);
            $times1 = explode(':', $times[1]);

            $data['publish_at'] = Carbon::create($times0[2], $times0[1], $times0[0], $times1[0], $times1[1], 0, 'Europe/Paris')->subHour(7);
        } else {
            $data['publish_at'] = Carbon::now('Europe/Paris');
        }

        return $data;
    }

    private function getParsedDesc($desc)
    {
        $parsedDesc = $this->markdown->parse($desc);
        return $parsedDesc;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        $video->delete();

        $user = Auth::user();
        if ($user) {
            Log::info($user->name . ' delete video ' . $id);
        }
        return response()->json([
            'status' => 200
        ]);
    }

    public function editPoints($id)
    {
        $video = Video::findOrFail($id);
        if ($video->points != '') {
            $edit = true;
        } else {
            $edit = false;
        }
        return view('admin.videos.point', compact(['video', 'edit']));
    }

    public function storePoints(Request $request, $id)
    {
        $video = Video::findOrFail($id);
        $video->points = $request->get('points', '');

        //重写content
        $subs = Helper::parseSubtitle($video->content);

        $points = explode(',', $video->points);
        foreach($subs as $i => $sub) {
            $sub->startTime = Helper::reverseSecond($points[$i]);
        }

        $video->content = Helper::composeSubtitle($subs);


        $video->save();

        return redirect('admin/videos');
    }

    public function getPoints($id)
    {
        $video = Video::findOrFail($id);
        return $video->points;
    }

    public function download($video_id)
    {
        $video = Video::findOrFail($video_id);
        $file_name = $video->originSrc . '.txt';

        $mime = 'application/force-download';
        header('Pragma: public'); // required
        header('Expires: 0'); // no cache
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private',false);
        header('Content-Type: '.$mime);
        header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
        header('Content-Transfer-Encoding: binary');
        header('Connection: close');

        echo htmlspecialchars_decode(Helper::extraFr($video->content));
        exit;
    }
}
