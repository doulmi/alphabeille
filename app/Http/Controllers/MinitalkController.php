<?php

namespace App\Http\Controllers;

use App\Minitalk;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;

class MinitalkController extends ReadableController
{
    public function index() {
        $pageLimit = Config::get('params')['pageLimit'];
        $minitalks = Minitalk::latest()->paginate($pageLimit);
        return view('minitalks.index', compact('minitalks'));
    }

    public function latest($num)
    {
        $minitalks = Minitalk::latest()->limit($num)->get();
        return $minitalks;
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
    public function show($idOrSlug)
    {
        $minitalk = Minitalk::findByIdOrSlugOrFail($idOrSlug);

        if(!$minitalk) {
        }
        $id = $minitalk->id;
//        Redis::incr('minitalk:view:' . $id);

        $comments = $minitalk->comments;
        $content = $minitalk->parsed_content;
        $wechat_part = $minitalk->parsed_wechat_part;

        list($like, $collect, $punchin) = $this->getStatus($minitalk);

        $readable = $minitalk;
        $type = 'minitalk';
        return view('minitalks.show', compact(['readable', 'type', 'comments', 'content', 'like', 'collect', 'punchin', 'wechat_part']));
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
    }

    public function free() {
        $minitalks = Minitalk::where('free', 1)->latest()->paginate($this->pageLimit);
        return view('$minitalks.index', compact('minitalks'));
    }


}
