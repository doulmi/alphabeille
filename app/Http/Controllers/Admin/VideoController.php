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
use Sunra\PhpSimple\HtmlDomParser;

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
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orderBy = Input::get('orderBy', 'created_at');
        $dir = Input::get('dir', 'DESC');
        $limit = Input::get('limit', 50);
        $search = trim(Input::get('search', ''));
        $searchField = trim(Input::get('searchField', ''));

        if ($searchField != '' && $search != '') {
            if ($searchField != 'role') {
                $videos = Video::where($searchField, 'LIKE', "%$search%")->orderBy($orderBy, $dir)->paginate($limit);
            }
        } else {
            $videos = Video::orderBy($orderBy, $dir)->paginate($limit);
        }
        return view('admin.videos.index', compact(['videos']));
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

        $video = Video::create($data);

        $user = Auth::user();
        if ($user) {
            Log::info($user->name . ' add video ' . $video->id);
        }
        Session::flash('success', trans('labels.createVideoSuccess'));
        return redirect('admin/videos');
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
        foreach($data as $key => $t) {
            $readable->$key =  $t;
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
        if ($data['preview']) {
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
        $content = str_replace('\n\n', '\n', $content);
        $data['content'] = $content;

        list($data['parsed_content'], $data['parsed_content_zh'], $data['points']) = Helper::parsePointLink($content);
        $data['parsed_desc'] = $this->getParsedDesc($data['description']);

        if (isset($data['publish_at']) && $data['publish_at'] != '') {
            $times = explode(' ', $data['publish_at']);
            $times0 = explode('/', $times[0]);
            $times1 = explode(':', $times[1]);

            $data['publish_at'] = $times0[2] . '-' . $times0[1] . '-' . $times0[0] . ' ' . $times1[0] . ':' . $times1[1] . ':00';
        } else {
            $data['publish_at'] = Carbon::now('Europe/Paris');
        }

        return $data;
    }

    private function getParsedDesc($desc) {
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
        $video->save();

        return redirect('admin/videos');
    }

    public function getPoints($id)
    {
        $video = Video::findOrFail($id);
        return $video->points;
    }
}
