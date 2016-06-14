<?php

namespace App\Http\Controllers\Api\Controllers;

use App\Http\Controllers\Api\Transformers\TopicTransformer;
use App\Topic;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class TopicController extends BaseApiController
{
    private $selectedCols = ['id','title', 'description', 'avatar', 'level'];
    /**
     * Display a listing of the resource.
     *
     * @param $count : 每一页展现的内容数
     * @param $page : 页数
     * @return \Illuminate\Http\Response
     */
    public function index($count, $page) {
        $topics = Topic::latest()->paginate($count, $this->selectedCols, 'page', $page);
        return $this->response->paginator($topics, new TopicTransformer());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $topic = Topic::find($id, $this->selectedCols);

        if (!$topic) {
            $this->response->errorNotFound();
        } else {
            return $this->response->item($topic, new TopicTransformer());
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
