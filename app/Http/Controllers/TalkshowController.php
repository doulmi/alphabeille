<?php

namespace App\Http\Controllers;

use App\Talkshow;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;

class TalkshowController extends Controller
{
    private $pageLimit = 48;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $talkshows = Talkshow::latest()->paginate($this->pageLimit);
        return view('talkshows.talkshows', compact('talkshows'));
    }

    public function latest($num) {
        $talkshows = Talkshow::latest()->limit($num)->get();
        return $talkshows;
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

    public function random($num = 4, $max = 100) {
        $talkshows = Talkshow::latest()->limit($max)->get();
        return $talkshows->random($num);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $talkshow = Talkshow::findOrFail($id);
        if( !$talkshow ) {
            abort(404);
        } else {
            $topics = App::make('App\Http\Controllers\TopicController')->random();
            $talkshows = App::make('App\Http\Controllers\TalkshowController')->random();
            return view('talkshows.talkshow', compact(['talkshow', 'topics', 'talkshows']));
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
