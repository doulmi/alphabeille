<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserLogin;
use App\Events\UserRegister;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Overtrue\Socialite\SocialiteManager;
use Validator;

class AuthController extends Controller
{
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

    protected $registerView = 'auth.login';

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
        $prevUrl = Redirect::getUrlGenerator()->previous();
        if (!str_contains($prevUrl, ['login', 'register'])) {
            Session::set('prevUrl', $prevUrl);
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
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
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        if ($data['email'][0])
            $user = User::create([
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'avatar' => '/img/default_avatar/' . strtoupper($data['email'][0]) . '.png',
                'name' => $this->autoName($data['email']),
                'confirmation_code' => str_random(64),
                'remember_token' => str_random(10),
                'download' => 4
            ]);

//        $member = Role::where('name', 'Member')->first();

//        $user->save();
//        $user->attachRole($member);

        event(new UserRegister($user));
        return $user;
    }

    private function authenticated(Request $request, $user)
    {
        event(new UserLogin());
//        $redirectUrl = $request->get('redirect_url', '/');
        return redirect(Session::get('prevUrl', '/'));
    }

    /**
     * Generate a defaut username by user's email
     * @param $email
     * @return mixed
     */
    private function autoName($email)
    {
        return explode('@', $email)[0];
    }

    public function facebookLogin()
    {
        $socialite = new SocialiteManager(config('services'));
        return $socialite->driver('facebook')->redirect();
    }

    public function facebookCallback()
    {
        $socialite = new SocialiteManager(config('services'));
        $user = $socialite->driver('facebook')->user();

        User::create([
            'email' => $user->getEmail(),
            'password' => bcrypt(str_random(16)),
            'name' => $user->getNickname(),
        ]);
    }

    public function wechatLogin()
    {

    }

    public function qqLogin()
    {
        $socialite = new SocialiteManager(config('services'));
        return $socialite->driver('qq')->redirect();
    }

    public function qqCallback()
    {
        $socialite = new SocialiteManager(config('services'));
        $user = $socialite->driver('qq')->user();

        if($user['original']['gender'] == '男') {
            $sex = 'male';
        } else if($user['original']['gender'] == '女') {
            $sex = 'female';
        } else {
            $sex = 'unknown';
        }

        $authUser = User::where('qq_id', $user->getId())->first();
        if(!$authUser) {
            //用户不存在，我们需要为其注册一个对应的用户
            $authUser = User::create([
                'password' => bcrypt(str_random(16)),
                'name' => $user->getNickname(),
                'qq_id' => $user->getId(),
                'avatar' => $user->getAvatar(),
                'sex' => $sex,
                'location' => $user['original']['province'] . ' ' . $user['original']['city'],
                'birthYear' => $user['original']['year'],
            ]);
        }
        \Auth::login($authUser);
        return redirect(Session::get('prevUrl', '/'));
    }

    public function githubLogin()
    {
        $socialite = new SocialiteManager(config('services'));
        return $socialite->driver('github')->redirect();
    }

    public function githubCallback()
    {
        $socialite = new SocialiteManager(config('services'));
        $user = $socialite->driver('github')->user();

        User::create([
            'email' => $user->getEmail(),
            'password' => bcrypt(str_random(16)),
            'name' => $user->getNickname(),
        ]);
    }
}
