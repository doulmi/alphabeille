<?php

namespace App\Http\Controllers;

use App\Collectable;
use App\Word;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class WordController extends Controller
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
     * @param  string $word
     * @return \Illuminate\Http\Response
     */
    public function show($word)
    {
        $word = Word::where('word', $word)->first();
//        $user = Auth::user();

        if($word) {
//            $isFav = Collectable::where('user_id', $user->id)->where('collectable_id', $word->id)->where('collectable_type', 'App\Word')->first() ? true : false;

            return response()->json([
                'status' => 200,
                'msg' => $word->explication
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'msg' => 'notFoundWord'
            ]);
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
