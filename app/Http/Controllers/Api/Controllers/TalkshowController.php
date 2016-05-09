<?php

namespace App\Http\Controllers\Api\Controllers;

use App\Http\Controllers\Api\Transformers\TalkshowTransformer;
use App\Talkshow;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class TalkshowController extends BaseApiController
{
    /**
     * TalkshowController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth.basic', ['only' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $num = Input::get('num', 8);
        $talkshows = Talkshow::latest()->limit($num)->get();
        return $this->response->collection($talkshows, new TalkshowTransformer());
    }

    public function latest($num) {
        $talkshows = Talkshow::latest()->limit($num)->get();
        return $this->response->collection($talkshows, new TalkshowTransformer());
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
        if (!$request->get('title') or !$request->get('description')) {
            return $this->response->errorUnauthorized();
        } else {
            Talkshow::create($request->all());
            return $this->setStatusCode(201)->responseSuccess(['Talkshow created']);
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
        $talkshow = Talkshow::findOrFail($id);

        if (!$talkshow) {
           return $this->response->errorNotFound('Talkshow not found');
        } else {
           return $this->item($talkshow, new TalkshowTransformer());
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
        //
    }


}
