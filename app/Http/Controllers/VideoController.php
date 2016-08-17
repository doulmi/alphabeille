<?php

namespace App\Http\Controllers;

use App\Editor\Markdown\Markdown;
use App\UserTraces;
use App\Video;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class VideoController extends ReadableController
{
    private $markdown;

    /**
     * @param $makrdown
     */
    public function __construct(Markdown $markdown)
    {
        $this->markdown = $markdown;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $idOrSlug)
    {
        $readable = Video::findByIdOrSlugOrFail($idOrSlug);

        Redis::incr('video:view:' . $readable->id);

        $readables = $this->random();
        $user = Auth::user();

        if($user) {
            UserTraces::create([
                'user_id' => $user->id,
                'readable_type' => 'App\Video',
                'readable_id' => $readable->id
            ]);
        }

        $fr = explode('||', $readable->parsed_content);
        $zh = explode('||', $readable->parsed_content_zh);

        list($like, $collect, $punchin) = $this->getStatus($readable);

        $type = 'video';
        $url = "http://api.wipmania.com/" . $request->ip();
//        $url = "http://api.wipmania.com/217.128.63.152";
        $response = $this->httpGet($url);

        $youtube = $response == 'CN' || $response == 'XX' ? false : true;
        return view('videos.show', compact(['readables', 'type', 'readable', 'fr', 'zh', 'like', 'collect', 'punchin', 'youtube']));
    }

    protected function httpGet($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
//        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
//        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
//        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
//        curl_setopt($curl, CURLOPT_CAINFO, public_path() . '/cacert.pem');//证书地址
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function level($level)
    {
        $pageLimit = config('params')['pageLimit'];
        $readables = Video::where('level', $level)->published()->latest()->orderBy('free')->paginate($pageLimit, ['id', 'avatar', 'title', 'slug', 'created_at', 'level']);
        $type = 'video';
        return view('videos.index', compact(['readables', 'type']));
    }
}
