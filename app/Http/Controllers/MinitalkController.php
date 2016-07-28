<?php

namespace App\Http\Controllers;

use App\Editor\Markdown\Markdown;
use App\Minitalk;
use App\MinitalkCollect;
use App\MinitalkFavorite;
use App\UserPunchin;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class MinitalkController extends ReadableController
{
    private $pageLimit = 24;

    //Redis中的次数累计到viewMax，写入到数据库中
    private $viewMax = 100;

    private $markdown;
    /**
     * @param $makrdown
     */
    public function __construct(Markdown $markdown)
    {
        $this->markdown = $markdown;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $minitalks = Minitalk::latest()->paginate($this->pageLimit);
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

        $id = $minitalk->id;
        Redis::incr('minitalk:view:' . $id);

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

    public function collectMinitalks()
    {
        $minitalks = MinitalkCollect::where('user_id', Auth::user()->id)->paginate($this->pageLimit);
        return view('minitalks.index', compact('minitalks'));
    }
}
