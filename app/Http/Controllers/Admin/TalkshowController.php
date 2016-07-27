<?php

namespace App\Http\Controllers\Admin;

use App\Helper;
use App\Talkshow;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class TalkshowController extends Controller {
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
        return view('admin.talkshows', compact(['talkshows']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $edit = false;
        return view('admin.talkshow', compact('edit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Talkshow::create(array_merge($request->all(), ['slug' => '']));
        Redis::incr('audio:count');
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
        return view('admin.talkshow', compact('edit', 'talkshow'));
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
        $talkshow->update($request->all());
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
