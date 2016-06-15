<?php

namespace App\Http\Controllers\API\Controllers;

use App\Http\Controllers\Api\Transformers\TalkshowTransformer;
use App\Talkshow;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class TalkshowController extends BaseApiController
{
    private $selectedCols = ['id','title', 'description', 'avatar', 'free', 'likes', 'views' ];
    /**
     * Display a listing of the resource.
     *
     * @param $count : 每一页展现的内容数
     * @param $page : 页数
     * @return \Illuminate\Http\Response
     */
    public function index($count, $page) {
        $topics = Talkshow::latest()->paginate($count, $this->selectedCols, 'page', $page);
        return $this->response->paginator($topics, new TalkshowTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $talkshow = Talkshow::find($id, $this->selectedCols);

        $user = $this->auth->user();

        if (!$talkshow) {
           return $this->response->errorNotFound();
        } else {
            return response()->json([
                'user_id' =>$user->id
            ]);
//           return $this->item($talkshow, new TalkshowTransformer());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
    }
}
