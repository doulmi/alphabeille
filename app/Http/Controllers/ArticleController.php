<?php

namespace App\Http\Controllers;

use App\Article;

class ArticleController extends Controller
{
    public function show($hash) {
        $article = Article::where('hash', $hash)->first();
        return view('articles.show', compact('article'));
    }
}
