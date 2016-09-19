<?php

namespace App\Http\Controllers\Admin;

use App\Word;
use App\WordFavorite;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use TomLingham\Searchy\Facades\Searchy;

class WordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = Input::get('limit', 50);
        $search = trim(Input::get('search', ''));

        if ($search != '') {
            $words= Word::where('word', 'like', $search)->paginate($limit);
        } else {
            $words = Word::orderBy('frequency', 'DESC')->paginate($limit);
        }
        return view('admin.words', compact(['words']));
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
        Word::create($request->all());
        $prev = redirect()->back()->getTargetUrl();
        if(str_contains($prev, 'unknown')) {
            return redirect($prev);
        } else {
            return redirect('admin/words');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $word = Word::findOrFail($id);
        $word->update($request->all());
//        return redirect('admin/words');
        return response()->json([
            'status' => 200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $word = Word::findOrFail($id);
        $word->delete();
        return response()->json([
            'status' => 200
        ]);
    }

    public function history() {
        $words = WordFavorite::with('word')->paginate(100);
        return view('admin.wordHistory', compact('words'));
    }

    public function addAudio($src) {
        $word = Word::where('word', $src)->first();
        if($word) {
            $word->audio = 'http://oc2ggunnp.bkt.clouddn.com/' . md5($src) . '.mp3';
            $word->update();
        }
        return response()->json([
            'status' => 200
        ]);
    }
}
