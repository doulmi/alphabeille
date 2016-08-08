<?php

namespace App\Http\Controllers\Admin;

use App\Editor\Markdown\Markdown;
use App\Talkshow;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class TalkshowController extends Controller {

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
    public function index()
    {
        $orderBy = Input::get('orderBy', 'created_at');
        $dir = Input::get('dir', 'DESC');
        $limit = Input::get('limit', 50);
        $search = trim(Input::get('search', ''));
        $searchField = trim(Input::get('searchField', ''));

        if ($searchField != '' && $search != '') {
            if ($searchField != 'role') {
                $talkshows = Talkshow::where($searchField, 'LIKE', "%$search%")->orderBy($orderBy, $dir)->paginate($limit);
            }
        } else {
            $talkshows = Talkshow::orderBy($orderBy, $dir)->paginate($limit);
        }
        return view('admin.talkshows.index', compact(['talkshows']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $edit = false;
        return view('admin.talkshows.show', compact('edit'));
    }

    private function getSaveData(Request $request) {
        $data = $request->all();
//        list($data['parsed_content'], $data['parsed_content_zh']) = Helper::parsePointLink($data['content']);
        $data['parsed_content'] = $this->markdown->parse($data['content']);

        if (isset($data['publish_at']) && $data['publish_at'] != '') {
            $times = explode(' ', $data['publish_at']);
            $times0 = explode('/', $times[0]);
            $times1 = explode(':', $times[1]);
            $pm = $times[2] == 'PM';

            $data['publish_at'] = $times0[2] . '-' . $times0[0] . '-' . $times0[1] . ' ' . ($pm ? $times1[0] + 12: $times1[0]) . ':' . $times1[1] . ':00';
        } else {
            $data['publish_at'] = Carbon::now('Europe/Paris');
        }

        return $data;
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

        Talkshow::create($data);
//        Redis::incr('audio:count');
        Session::flash('success', trans('labels.createTalkshowSuccess'));
        return redirect('admin/talkshows');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $talkshow = Talkshow::firstOrFail($id);
        return $talkshow;
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
        $talkshow = Talkshow::findOrFail($id);

        $time = $talkshow->publish_at;

        $talkshow->showTime = $time->day . '/' . $time->month . '/' . $time->year . ' ' . $time->hour . ':' . $time->minute;
        return view('admin.talkshows.show', compact('edit', 'talkshow'));
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
        $talkshow = Talkshow::findOrFail($id);

        $data = $this->getSaveData($request);

        $talkshow->update($data);
        return redirect('admin/talkshows');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $talkshow = Talkshow::findOrFail($id);
        $talkshow->delete();
        return response()->json([
            'status' => 200
        ]);
    }
}
