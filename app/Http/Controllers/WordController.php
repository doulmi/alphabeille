<?php

namespace App\Http\Controllers;

use App\Collectable;
use App\Word;
use App\WordFavorite;
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

    public function getWord($src) {
        $src = trim($src);
        $word = Word::where('word', $src)->first();
        $origin = false;
        if ($word) {
            $origin = true;
        }

        if (str_contains('\'', $src)) {
            $src = explode('\'', $src)[1];
            $word = Word::where('word', $src)->first();
        }

        //阳性,复数
        if (!$word && ends_with($src, 's')) {
            $word = Word::where('word', substr($src, 0, -1))->first();
        }

        //阴性,复数
        if (!$word && ends_with($src, 'es')) {
            $word = Word::where('word', substr($src, 0, -2))->first();
        }

        //阴性
        if (!$word && ends_with($src, 'e')) {
            $word = Word::where('word', substr($src, 0, -1))->first();
        }

        /** 第一组动词 **/
        //0.现在时, 现在分词, 复合过去时
        if (!$word && preg_match('/(.*)(e|es|ons|ez|ent|ant|é)$/', $src, $data)) {
            $word = Word::where('word', $data[1] . 'er')->first();
        }

        //2. 未完成过去式
        if (!$word && preg_match('/(.*)(ai(s|t|ent)|i(ons|ez))$/', $src, $data)) {
            $word = Word::where('word', $data[1] . 'er')->first();
        }
        //3. 简单过去式, 简单将来时，条件现在时
        if (!$word && preg_match('/(.*)(erai(t|s)?|eras|era|âmes|erons|erions|âtes|erez|eriez|èrent|eront|eraient)$/', $src, $data)) {
            $word = Word::where('word', $data[1] . 'er')->first();
        }

        //由于ai|as|a会强制替代掉上面正则的erai, eras, era所以需要单独拿出来
        if (!$word && preg_match('/(.*)(ai|as|a)/', $src, $data)) {
            $word = Word::where('word', $data[1] . 'er')->first();
        }

        /* 第二组动词 */
        //现在时，复合过去时，未完成过去式, 现在分词
        if (!$word && preg_match('/(.*)(issais|iss|issait|issiez|issez|issi?ons|issaient|iss(e|a)nt|i)$/', $src, $data)) {
            $word = Word::where('word', $data[1] . 'ir')->first();
        }

        //由于is|it会替换上面的issais|issait, 所以单独拿出来
        if (!$word && preg_match('/(.*)(is|it)$/', $src, $data)) {
            $word = Word::where('word', $data[1] . 'ir')->first();
        }

        //简单过去式，简单将来时，条件现在时
        if (!$word && preg_match('/(.*)(irai(t|s)?|irai?s|isses?|issi?(ons|ent|iez)|ira|îmes|iraient|iri?ons|îtes|iri?ez|ir(e|o)nt)$/', $src, $data)) {
            $word = Word::where('word', $data[1] . 'ir')->first();
        }

        //eaux复数
        if (!$word && ends_with($src, 'x')) {
            $word = Word::where('word', substr($src, 0, -1))->first();
        }

        //去掉accent
        if( !$word ) {
            $word = Word::where('word', str_slug($src))->first();
        }
        return [$word, $origin];
    }
    /*
     * Display the specified resource.
     *
     * @param  string $src
     * @return \Illuminate\Http\Response
     */
    public function show($src, $readable_id, $readable_type)
    {
        list($word, $origin) = $this->getWord($src);

        if ($word) {
            $this->favorite($word, $readable_id, $readable_type);
            if (!$origin) {
                $word->explication = $word->word . '<br/>' . $word->explication;
            }

            return response()->json([
                'status' => 200,
                'msg' => $word->explication,
                'audio' => $word->audio,
                'freq' => $word->frequency
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $word = WordFavorite::find($id);
        $word->delete();
    }

    private function favorite($word, $readable_id, $readable_type)
    {
        $user = Auth::user();
        if ($user) {
            switch ($readable_type) {
                case 'video' :
                    $readable_type = 'App\Video';
                    break;
                case 'minitalk' :
                    $readable_type = 'App\Minitalk';
                    break;
                case 'talkshow' :
                    $readable_type = 'App\Talkshow';
                    break;
                case 'lesson' :
                    $readable_type = 'App\Lesson';
            }

            $wf = WordFavorite::where('user_id', $user->id)->where('word_id', $word->id)->first();
            if($wf) {
                $wf->times ++;
                $wf->readable_type = $readable_type;
                $wf->readable_id = $readable_id;
                $wf->save();
            } else {
                WordFavorite::create([
                    'user_id' => $user->id,
                    'word_id' => $word->id,
                    'readable_type' => $readable_type,
                    'times' => 1,
                    'readable_id' => $readable_id
                ]);
            }
        }
    }
}
