<?php

namespace App\Http\Controllers\Admin;

use App\Topic;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class TopicController extends Controller
{
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
                $topics = Topic::where($searchField, 'LIKE', "%$search%")->orderBy($orderBy, $dir)->paginate($limit);
            }
        } else {
            $topics = Topic::orderBy($orderBy, $dir)->paginate($limit);
        }
        return view('admin.topics', compact(['topics']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        if($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
//            $destPath = Config::get('upload')['topic'];
//            $name = time() . '.jpg';
//            $request->file('avatar')->move($destPath, $name);

        Topic::create($request->all());

        Session::flash('success', trans('labels.createTopicSuccess'));
        return redirect('admin/topics');
//        } else {
//            Session::flash('errors', trans('labels.createTopicFailed'));
//            return redirect('admin/topics');
//        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $topic = Topic::find($id);
        if ($topic) {
            $topic->update($request->all());
            Session::flash('success', trans('labels.createTopicSuccess'));
        } else {
            Session::flash('fail', trans('labels.createTopicFailed.cannotFound'));
        }
        return redirect('admin/topics');

//        if (!$topic) {
//            return response()->json([
//                'status' => 404
//            ]);
//        } else {
//            $topic->title = $request->get('title');
//            $topic->description = $request->get('description');
//            $topic->avatar = $request->get('avatar');
//            $topic->level = $request->get('');
//            $topic->update();
//
//            return response()->json([
//                'status' => 200
//            ]);
//        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $topic = Topic::findOrFail($id);
        $topic->delete();
        return response()->json([
            'status' => 200
        ]);
    }
}
