<?php

namespace App\Http\Controllers;

use App\Article;
use Carbon\Carbon;

class ArticleController extends Controller
{
    public function show($hash) {
        $article = Article::where('hash', $hash)->first();
        return view('articles.show', compact('article'));
    }

    public function index() {
        $finds = Article::all();
        $articles = [];
        $now = Carbon::now();
        foreach($finds as $article) {
            $dates = explode('-', $article->reciteAt);
            $date = Carbon::createFromDate($dates[0], $dates[1], $dates[2]);
            if($now->gte($date)) {
                $articles[] = $article;
            }
        }
        return view('articles.index', compact('articles') );
    }
}
