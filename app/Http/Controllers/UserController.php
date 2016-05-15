<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    public function profile($id) {
        $targetUser = User::findOrFail($id);

        if($targetUser) {
            $loginUser = Auth::user();
            if($loginUser && $loginUser->id == $targetUser->id) {
                return view('user.profile', compact('targetUser'));
            } else {
                return view('user.info', compact('targetUser'));
            }
        } else {
            abort('404');
        }
    }
}
