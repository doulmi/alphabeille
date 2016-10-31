<?php

namespace App\Http\Controllers\Admin;

use App\Article;
use App\Editor\Markdown\Markdown;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    private $markdonw;

    public function __construct(Markdown $markdown) {
       $this->markdonw = $markdown;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::paginate(50);
        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $edit = false;
        return view('admin.articles.show', compact('edit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['parsedContent'] = $this->markdonw->parse($data['content']);
        $data['hash'] = md5($data['reciteAt']);
        Article::create($data);
        return redirect('admin/articles');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit = true;
        $article = Article::findOrFail($id);
        return view('admin.articles.show', compact('edit', 'article'));
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
        $article = Article::findOrFail($id);
        $data = $request->all();
        $data['parsedContent'] = $this->markdonw->parse($data['content']);
        $data['hash'] = md5($data['reciteAt']);
        $article->update($data);
        return redirect('admin/articles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Article::findOrFail($id)->delete();
        return response()->json([
            'status' => 200
        ]);
    }
}
