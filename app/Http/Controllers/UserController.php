<?php

namespace App\Http\Controllers;

use App\LessonCollect;
use App\LessonFavorite;
use App\TalkshowCollect;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
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
        $lessons = LessonCollect::where('user_id', Auth::user()->id);
        $talkshows = TalkshowCollect::where('user_id', Auth::user()->id);
        return view('collect', compact('lessons', 'talkshows'));
    }
}
