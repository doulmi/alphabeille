<?php

namespace App\Http\Controllers\Api\Controllers;

use App\Http\Controllers\Api\Transformers\TopicTransformer;
use App\Topic;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class TopicController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $num = Input::get('num', 8);
        $topics = Topic::latest()->paginate(8);
        return $this->response->paginator($topics, new TopicTransformer());
//        return $this->response->collection($topics, new TopicTransformer());
    }


    /**
     * 从最新的100个里面随机取出的n条数据
     * @return \Dingo\Api\Http\Response
     */
    public function random() {
        $num = Input::get('num', 4);
        $max = Input::get('max', 100);
        $topics = Topic::latest()->limit($max)->get();

        return $this->response->collection($topics->random($num), new TopicTransformer());
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $talkshow = Topic::findOrFail($id);

        if (!$talkshow) {
            $this->response->errorNotFound();
        } else {
            return $this->response->item($talkshow, new TopicTransformer());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
