<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

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

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
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
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function redirectToProvider(\Request $request, $provider)
    {
      return \Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(\Request $request, $provider)
    {
      try {
        $user = \Socialite::driver($provider)->user();
      }
      catch (Exception $e) {
        return \Redirect::to("auth/$provider");
      }

      $authUser = $this->findOrCreateUser($user, $provider);

      \Auth::login($authUser);

      return \Redirect::to('/');
    }

    private function findOrCreateUser($providerUser, $provider)
    {
      if ($authUser = User::where("{$provider}_id", $providerUser->id)->first()) {
        return $authUser;
      }

      return User::create([
        'name'            => $providerUser->getName(),
        'email'           => $providerUser->getEmail(),
        "{$provider}_id"  => $providerUser->getId(),
        'avatar'          => $providerUser->getAvatar()
      ]);
    }
}
