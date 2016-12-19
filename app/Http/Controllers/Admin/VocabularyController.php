<?php

namespace App\Http\Controllers\Admin;

use App\Editor\Markdown\Markdown;
use App\Helper;
use App\Http\Controllers\Controller;
use App\Vocabulary;
use Illuminate\Http\Request;


class VocabularyController extends Controller
{
  private $markdonw;

  public function __construct(Markdown $markdown)
  {
    $this->markdonw = $markdown;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $vocabularies = Vocabulary::paginate(50);
    return view('admin.vocabularies.index', compact('vocabularies'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $edit = false;
    return view('admin.vocabularies.show', compact('edit'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $data = $request->all();
    $data['parsedContent'] = $this->markdonw->parse($data['content']);
    $data['parsedContent'] = Helper::emberedWord($data['parsedContent']);
    dd($data['parsedContent']);
    $data['parsedContent'] = preg_replace('/{(.*)\/}/U', "<audio id='audio' controls hidden loop preload='auto' src='http://o9dnc9u2v.bkt.clouddn.com/vocabulary/$1.mp3'></audio>", html_entity_decode($data['parsedContent']));
    $data['hash'] = md5($data['date']);
    Vocabulary::create($data);
    return redirect('admin/vocabularies');
  }

  /**
   * Display the specified resource.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $edit = true;
    $vocabulary = Vocabulary::findOrFail($id);
    return view('admin.vocabularies.show', compact('edit', 'vocabulary'));
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
    $article = Vocabulary::findOrFail($id);
    $data = $request->all();
    $data['parsedContent'] = $this->markdonw->parse($data['content']);
    $data['parsedContent'] = Helper::emberedWord(html_entity_decode($data['parsedContent']));

    $data['parsedContent'] = preg_replace('/{<span>(.*)<\/span>\/<span>(.*)<\/span>\/}/', '{$1/$2/}', $data['parsedContent']);
    $data['parsedContent'] = preg_replace('/<;hr\/>;/', '<hr/>', $data['parsedContent']);
    $data['parsedContent'] = preg_replace('/{(.*)\/}/U', "<audio id='audio' controls hidden loop preload='auto' src='http://o9dnc9u2v.bkt.clouddn.com/vocabulary/$1.mp3'></audio>", $data['parsedContent']);
    $data['hash'] = md5($data['date']);
    $article->update($data);
    return redirect('admin/vocabularies');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    Vocabulary::findOrFail($id)->delete();
    return response()->json([
      'status' => 200
    ]);
  }
}


