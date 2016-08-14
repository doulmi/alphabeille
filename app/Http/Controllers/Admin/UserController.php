<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\UserTraces;
use Bican\Roles\Models\Role;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function traces($userId)
    {
        $traces = UserTraces::where('user_id', $userId)->latest()->paginate(100);
        $user = User::findOrFail($userId);
        return view('admin.traces', compact(['traces', 'user']));
    }
    public function index()
    {
        $orderBy = Input::get('orderBy', 'created_at');
        $dir = Input::get('dir', 'DESC');
        $limit = Input::get('limit', 50);
        $search = trim(Input::get('search', ''));
        $searchField = trim(Input::get('searchField', ''));

        if ($orderBy == 'level') {
        } else {
            if($searchField != '' && $search != '') {
                if( $searchField != 'role') {
                    $users = User::where($searchField, 'LIKE', "%$search%")->orderBy($orderBy, $dir)->paginate($limit);
                } else {
                    $users = User::with('roles')->get();
                    $filters = $users->filter(function($item) use ($search) {
                        $item->is(strtolower($search));
                    });

                    $users = $filters->paginate($limit);;
                }
            } else {
                $users = User::orderBy($orderBy, $dir)->paginate($limit);
            }
        }
        $loginUser = Auth::user();
        $roles = Role::all();
        return view('admin.users', compact(['users', 'roles', 'loginUser']));
    }

    public function store(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $email = $request->get('email');

        $user = User::create([
            'email' => $email,
            'password' => bcrypt($request->get('password')),
            'avatar' => '/img/default_avatar/' . strtoupper($email[0]) . '.png',
            'name' => explode('@', $email)[0],
            'confirmation_code' => str_random(64),
            'confirmed' => 1,
            'remember_token' => str_random(10),
            'download' => 4
        ]);
        $user->save();
        Session::flash('success', 'addSuccess');
        return redirect('admin/users');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
    }

    public function changeRole($userId, $roleId)
    {
        $data = [];
        if (Auth::user()->isAdmin()) {
            $user = User::findOrFail($userId);
            if ($user) {
                $role = Role::findOrFail($roleId);
                if ($role) {
                    $user->detachAllRoles();
                    $user->attachRole($role);
                    $user->save();

                    $data['status'] = '200';
                    $data['data']['message'] = 'success';
                } else {
                    $data['data']['message'] = 'roleNotFound';
                    $data['status'] = '403';
                }
            } else {
                $data['data']['message'] = 'userNotFound';
            }
        } else {
            $data['status'] = '401';
            $data['data']['message'] = 'Unauthorized';
        }
        return response()->json($data);
    }
}
