<?php

namespace App\Http\Controllers\API\Controllers;

use App\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Transformers\MessageTransformer;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class MessageController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $userId = Input::get('id');
        if(!$userId) {
            return $this->response->errorUnauthorized();
        } else {
            $message = Message::where('to', $userId)->where('id', $id)->first();
            if(!$message->isRead) {
               $message->isRead = true;
               $message->save();
            }

            if (!$message) {
                $this->response->errorNotFound();
            } else {
                return $this->response->item($message, new MessageTransformer());
            }
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
