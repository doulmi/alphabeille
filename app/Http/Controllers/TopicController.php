<?php

namespace App\Http\Controllers;

use App\Topic;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;

class TopicController extends Controller
{
    private $pageLimit = 24;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topics = Cache::remember('topics', 22 * 60, function() {
            return Topic::latest()->paginate($this->pageLimit);
        });
        return view('topics.index', compact('topics'));
    }


    public function latest($num)
    {
        $topics = Topic::latest()->limit($num)->get();
        foreach($topics as $topic) {
            $states = $topic->getState();
            $topic->isUpdated = $states['isUpdated'];
            $topic->isNew = $states['isNew'];
        }
        return $topics;
    }

    public function random($num = 4, $max = 100) {
//        $topics = Cache::remember('randomTopics', 60, function() use ($max, $num) {
            $topics = Topic::latest()->limit($max)->get();
            return $topics->random($num);
//        });
//        return $topics;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $topic = Topic::findOrFail($id);
        if($topic) {
            return view('topics.show', compact('topic'));
        } else {
            return view('errors.404');
        }
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
        //
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

    /**
     * Remove the specified resources
     * @param $ids : 1,3,4
     */
    public function multiDestroy($ids) {
        dd($ids);
    }
}
