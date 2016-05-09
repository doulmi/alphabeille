<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegister;
use App\User;
use Faker\Generator;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{

    protected $redirectPath = '';
    protected $registerView = 'auth.login';
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        /**
         * 'abstract', 'animals', 'business', 'cats', 'city', 'food', 'nightlife',
         *  'fashion', 'people', 'nature', 'sports', 'technics', 'transport'
         */
        $user = User::create([
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'avatar' => '/img/default_avatar/' . strtoupper($data['email'][0]) . '.png',
            'confirmation_code' => str_random(64),
            'remember_token' => str_random(10),
        ]);

        $user->name = $this->autoName($user->email);
        $user->save();

        event(new UserRegister($user));
        return $user;
    }

    /**
     * Generate a defaut username by user's email
     * @param $email
     * @return mixed
     */
    private function autoName($email) {
        return explode('@', $email)[0];
    }
}
