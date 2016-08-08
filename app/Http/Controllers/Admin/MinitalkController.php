<?php

namespace App\Http\Controllers\Admin;

use App\Editor\Markdown\Markdown;
use App\Helper;
use App\Minitalk;
use App\Talkshow;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class MinitalkController extends Controller {

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orderBy = Input::get('orderBy', 'created_at');
        $dir = Input::get('dir', 'DESC');
        $limit = Input::get('limit', 50);
        $search = trim(Input::get('search', ''));
        $searchField = trim(Input::get('searchField', ''));

        if ($searchField != '' && $search != '') {
            if ($searchField != 'role') {
                $minitalks = Talkshow::where($searchField, 'LIKE', "%$search%")->orderBy($orderBy, $dir)->paginate($limit);
            }
        } else {
            $minitalks = Minitalk::orderBy($orderBy, $dir)->paginate($limit);
        }
        return view('admin.minitalks.index', compact(['minitalks']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $edit = false;
        return view('admin.minitalks.show', compact('edit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->getSaveData($request);
        $data['slug'] = '';

        Minitalk::create($data);
//        Redis::incr('audio:count');
        Session::flash('success', trans('labels.createMinitalkSuccess'));
        return redirect('admin/minitalks');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $minitalk = Minitalk::firstOrFail($id);
        return $minitalk;
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
        $minitalk = Minitalk::findOrFail($id);
        $time = $minitalk->publish_at;
        $isPm = $time->hour >= 12? true: false;

        $minitalk->showTime = $time->day . '/' . $time->month . '/' . $time->year . ' ' .  ($isPm ? $time->hour - 12 : $time->hour) . ':' . $time->minute .  ($isPm ? ' PM' : ' AM');
        return view('admin.minitalks.show', compact('edit', 'minitalk'));
    }

    private function getSaveData(Request $request) {
        $data = $request->all();

        $parsed_content = str_replace('‘', '\'', $data['content']);
        $parsed_content = str_replace('’', '\'', $parsed_content);
        $parsed_content = str_replace('《', '«', $parsed_content);
        $parsed_content = str_replace('》', '»', $parsed_content);
        $parsed_content = str_replace('  ', ' ', $parsed_content);
        $parsed_content = str_replace('，', ',', $parsed_content);
        $parsed_content = str_replace('。', '.', $parsed_content);

        $data['parsed_content'] = Helper::emberedWord($this->markdown->parse($parsed_content));
        $data['parsed_wechat_part'] = $this->markdown->parse($data['wechat_part']);

        if (isset($data['publish_at']) && $data['publish_at'] != '') {
            $times = explode(' ', $data['publish_at']);
            $times0 = explode('/', $times[0]);
            $times1 = explode(':', $times[1]);
            $pm = $times[2] == 'PM';

            $data['publish_at'] = $times0[2] . '-' . $times0[0] . '-' . $times0[1] . ' ' . ($pm ? $times1[0] + 12: $times1[0]) . ':' . $times1[1] . ':00';
        } else {
            $data['publish_at'] = Carbon::now('Europe/Paris');
        }
        return $data;
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
        $data = $this->getSaveData($request);
        $minitalk = Minitalk::findOrFail($id);
        $minitalk->update($data);
        return redirect('admin/minitalks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $minital = Minitalk::findOrFail($id);
        $minital->delete();
        return response()->json([
            'status' => 200
        ]);
    }
}
