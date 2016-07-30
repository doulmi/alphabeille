<?php

namespace App\Http\Controllers\Admin;

use App\Editor\Markdown\Markdown;
use App\Helper;
use App\Video;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['slug'] = '';
//        $data['parsed_content'] = Helper::parsePointLink($this->markdown->parse($data['content']));
        list($data['parsed_content'], $data['parsed_content_zh']) = Helper::parsePointLink($data['content']);

        Video::create($data);

        Redis::incr('audio:count');
        Session::flash('success', trans('labels.createVideoSuccess'));
        return redirect('admin/videos');
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $video = Video::firstOrFail($id);
        return $video;
    }

    public function preview(Request $request) {
        $tmp = $request->all();
        return view('admin.videos.preview', compact('tmp'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit = true;
        $video = Video::findOrFail($id);
        return view('admin.videos.show', compact(['edit', 'video']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);

        $data = $request->all();
        list($data['parsed_content'], $data['parsed_content_zh']) = Helper::parsePointLink($data['content']);

        $video->update($data);
        return redirect('admin/videos');
    }

    public function testHelper() {
        $video = Video::findOrFail(1);
        Helper::parsePointLink($video->content);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        $video->delete();
        return response()->json([
            'status' => 200
        ]);
    }

    public function editPoints($id) {
        $video = Video::findOrFail($id);
        if($video->points != '') {
            $edit = true;
        } else {
            $edit = false;
        }
        return view('admin.videos.point', compact(['video', 'edit']));
    }

    public function storePoints(Request $request, $id) {
        $video = Video::findOrFail($id);
        $video->points = $request->get('points', '');
        $video->save();

        return redirect('admin/videos');
    }

    public function getPoints($id) {
        $video = Video::findOrFail($id);
        return $video->points;
    }
}
