<?php

namespace App\Http\Controllers;

use App\Collectable;
use App\Lesson;
use App\Minitalk;
use App\Task;
use App\User;
use App\Video;
use App\Word;
use App\WordFavorite;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function confirmEmail($confirmation_code)
    {
        $user = User::where('confirmation_code', $confirmation_code)->first();
        if (is_null($user)) {
            return redirect('/');
        } else {
            $user->confirmed = 1;
            $user->confirmation_code = str_random(64);
            $user->save();
            return redirect('/');
        }
    }

    public function emailExit($email) {
        $user = User::where('email', $email)->first();
        return $user == null ? false : true;
    }

    public function index() {
        return User::latest()->paginate();
    }

    public function uploadAvatar(Request $request) {
        $user = $request->user();
        $avatar = $request->file('avatar');

        //判断上传文件是否是图片
        $input = ['image' => $avatar];
        $rules = ['image' => 'image'];

        $validator = Validator::make($input, $rules);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ]);
        }

        $destinationPath = 'img/avatar/';
        $filename = 'avatar-' . $user->id . '-' . time() . '.jpg';
        $avatar->move($destinationPath, $filename);

        Image::make($destinationPath . $filename)->fit(200)->save();
        $user->avatar = '/' . $destinationPath . $filename;
        $user->save();

        return response()->json( [
                'success' => true,
                'avatar' => asset($destinationPath . $filename),
            ]
        );
    }

    public function update(Request $request) {
        $user = $request->user();

        $user->name = $request->get('name', $user->name);
        $user->QQ = $request->get('qq', $user->qq);
        $user->wechat = $request->get('wechat', $user->wechat);

        $user->save();
        Session::flash('saveSuccess', 'saveSuccess');
        return Redirect::back();
    }

    public function modifyPwd(Request $request) {
        $user = $request->user();

        $oldPwd = $request->get('oldPwd');
        $newPwd = $request->get('newPwd');

        if(bcrypt($oldPwd) == $user->password) {
            $user->password = bcrypt($newPwd);

            $user->save();
            Session::flash('saveSuccess', 'saveSuccess');
            return Redirect::back();
        } else {
            Session::flash('error', 'wrongOldMessage');
            return Redirect::back();
        }
    }

    public function show($id) {
        $targetUser = User::findOrFail($id);

        if($targetUser) {
            $loginUser = Auth::user();
            if($loginUser && $loginUser->id == $targetUser->id) {
                return view('users.show', ['user' => $targetUser]);
            } else {
                return view('users.info', ['user' => $targetUser]);
            }
        } else {
            abort('404');
        }
    }

    public function collect() {
        $userId = Auth::user()->id;
        $minitalksIds = Collectable::where('user_id', $userId)->where('collectable_type', 'App\Minitalk')->lists('collectable_id')->toArray();
        $videosIds = Collectable::where('user_id', $userId)->where('collectable_type', 'App\Video')->lists('collectable_id')->toArray();

        $minitalks = Minitalk::whereIn('id', $minitalksIds)->get(['id', 'avatar', 'title', 'slug']);
        $videos = Video::whereIn('id', $videosIds)->get(['id', 'avatar', 'title', 'slug']);

        return view('users.collect', compact(['minitalks', 'videos']));
    }

    public function words() {
        $user = Auth::user();
        $words = Word::join('word_favorites', 'words.id', '=', 'word_favorites.word_id')->where('user_id', $user->id)->latest()->paginate(50, ['word_favorites.created_at', 'word_favorites.times', 'words.word', 'words.explication', 'words.id', 'word_favorites.updated_at', 'word_favorites.readable_id', 'word_favorites.readable_type']);

        $wordss = [];
        foreach($words as $word) {
            $updated_at = $word->updated_at;
            $wordss[$updated_at->day . '/' . $updated_at->month . '/' . $updated_at->year][] = $word;
        }
        return view('users.words', compact('wordss'));
    }

    public function translators() {
        $userIds = DB::table('role_user')->whereIn('role_id', [1, 5, 6])->distinct()->lists('user_id');
        $translators = User::whereIn('id', $userIds)->get(['id', 'avatar', 'name']);

        $translators = $translators->sortByDesc(function($translator) {
            return $translator->translatedNumber();
        });

        return view('translators', compact('translators'));
    }

    public function translateVideos($userId) {
        $readables = Video::join('tasks', 'tasks.video_id', '=', 'videos.id')->where('tasks.user_id', $userId)->where('tasks.is_submit', 1)->orderby('videos.updated_at', 'DESC')->paginate(48, ['videos.id', 'avatar', 'title', 'slug', 'videos.created_at','level', 'free', 'state']);

        $type = 'video';
        return view('videos.index', compact('readables', 'type'));
    }
}
