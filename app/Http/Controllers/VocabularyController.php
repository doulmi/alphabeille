<?php

namespace App\Http\Controllers;

use App\Vocabulary;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redis;

class VocabularyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $vocabularies = Vocabulary::orderBy('date')->paginate(40);
      return view('vocabularies.index', compact('vocabularies'));
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
    public function show($hash)
    {
      $vocabulary = Vocabulary::where('hash', $hash)->first();
      if ($vocabulary) {
        return view('vocabularies.show', compact('vocabulary'));
      } else {
        return abort(404);
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

    public function login(Request $request) {
      $student = $request->get('student');
    }

    public function loginForm() {
      return view('vocabularies.loginForm');
    }

    public function check($token) {
      if(trim($token) != '') {
        $today = Carbon::now()->format('Y-m-d');
        $result = Redis::set($token . ':' . $today, 1);
        if($result == 'OK') {
          return [ 'success' => true ];
        } else {
          return ['success' => false ];
        }
      } else {
        return redirect('vocabularies/loginForm');
      }
    }
}
