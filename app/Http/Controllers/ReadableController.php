<?php

namespace App\Http\Controllers;

use App\Collectable;
use App\Commentable;
use App\Likeable;
use App\UserPunchin;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class ReadableController extends Controller
{
    public function comments($id) {
        $model = $this->getModel();
        $readable = $model::findOrFail($id, ['id']);
        $rawComments = $readable->comments()->latest()->get();
        $comments = [];

        foreach($rawComments as $rawComment) {
            $comment = new \stdClass();
            $comment->userId = $rawComment->owner->id;
            $comment->avatar = $rawComment->owner->avatar;
            $comment->name = $rawComment->owner->name;
            $comment->content = $rawComment->content;
            $comment->created_at = $rawComment->created_at->diffForHumans();
            $comments[] = $comment;
        }
        return $comments;
    }

    /**
     * 取得雷鸣，包含namespace
     * @return string
     */
    private function getModel() {
        return 'App\\' . str_replace('Controller', '', class_basename(get_class($this)));
    }

    /**
     * 取得类名，不包含namespace
     */
    private function getType() {
        return lcfirst(str_replace('Controller', '', class_basename(get_class($this))));
    }

    public function addComment(Request $request) {
        $model = $this->getModel();
        $classStr = $model;
        $readable = $model::findOrFail($request->get('id'));
        $content = $request->get('content');
        $user = Auth::user();

        Commentable::create([
            'commentable_id' => $readable->id,
            'user_id' => $user->id,
            'content' => $content,
            'commentable_type' => $classStr
        ]);

        return response()->json([
            'status' => 200,
            'content' => $content,
            'user_id' => $user->id,
            'id' => $readable->id
        ]);
    }

    public function favorite($id)
    {
        return $this->doAction($id, Likeable::class, 'likeable');
    }

    public function collect($id)
    {
        return $this->doAction($id, Collectable::class, 'collectable');
    }

    protected function getStatus($entity)
    {
        $like = false;
        $collect = false;
        $punchin = false;

        if (!Auth::guest()) {
            $model = $entity->likes()->where('user_id', Auth::user()->id)->first();
            if ($model) {
                $like = true;
            }
            $model = $entity->collects()->where('user_id', Auth::user()->id)->first();
            if ($model) {
                $collect = true;
            }

            $model = UserPunchin::where('user_id', Auth::user()->id)->whereDate('created_at', '=', Carbon::today()->toDateString())->first();
            if ($model) {
                $punchin = true;
            }
        }

        return [$like, $collect, $punchin];
    }

    public function punchin($id)
    {
        $user = Auth::user();
        if(!$user) {
            return response()->json([
                'status' => 403,
            ]);
        }
        $punchin = UserPunchin::where('user_id', $user->id)->whereDate('created_at', '=', Carbon::today()->toDateString())->first();
        if (!$punchin) {
            UserPunchin::create([
                'punchable_type' => $this->getModel(),
                'punchable_id' => $id,
                'user_id' => $user->id
            ]);

            $break = false;
            $user->series++;
            $shouldUpdateMaxSeries = $user->series > $user->maxSeries;
            if ($shouldUpdateMaxSeries) {
                $user->maxSeries = $user->series;
                $break = true;
            }
            $user->save();
            return response()->json([
                'status' => 200,
                'break' => $break,
                'series' => $user->series
            ]);
        }
    }

    private function doAction($id, $class, $type)
    {
        //不登录没权限
        if (Auth::guest()) {
            return response()->json([
                'status' => 403,
            ]);
        }

        $model = $this->getModel();

        $entity = $class::where([
            $type . '_id' => $id,
            $type . '_type' => $model,
            'user_id' => Auth::user()->id
        ])->first();

        //已经收藏或喜欢的话会取消
        if ($entity) {
            $entity->delete();

            return response()->json([
                'status' => 200
            ]);
        } else {
            $class::create([
                $type . '_id' => $id,
                $type . '_type' => $model,
                'user_id' => Auth::user()->id
            ]);

            return response()->json([
                'status' => 200
            ]);
        }
    }

    public function collects(Request $request) {
        $pageLimit = config('params')['pageLimit'];
        $model = $this->getModel();
        $minitalks = Collectable::where('user_id', Auth::user()->id)->where('collectable_type', $model)->paginate($pageLimit)->appends($request->all());
        return view('minitalks.index', compact('minitalks'));
    }

    public function free(Request $request) {
        $model = $this->getModel();
        $type = $this->getType();
        $pageLimit = config('params')['pageLimit'];
        $minitalks = $model::where('free', 1)->latest()->paginate($pageLimit)->appends($request->all());
        return view( $type . 's.index', compact( $type . 's'));
    }

    public function index(Request $request) {
        $model = $this->getModel();
        $type = $this->getType();
        $pageLimit = config('params')['pageLimit'];
        if($type == 'video') {
            $cols = ['id', 'avatar', 'title', 'slug', 'created_at','level', 'free', 'state'];
        } else {
            $cols = ['id', 'avatar', 'title', 'slug', 'created_at', 'free'];
        }
        $readables = $model::published()->orderBy('free', 'DESC')->latest()->paginate($pageLimit, $cols)->appends($request->all());
        return view($type . 's.index', compact(['readables', 'type']));
    }

    public function random($num = 4, $max = 100)
    {
        $model = $this->getModel();
        $readable = $model::latest()->limit($max)->get();
        $num = $num > $readable->count() ? : $num;
        return $readable->random($num);
    }
}
