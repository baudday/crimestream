<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

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

    protected $redirectPath = '/account';

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
        $hash = md5($data['email']);
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'avatar' => "//www.gravatar.com/avatar/$hash?d=mm"
        ]);
    }

    public function redirectToProvider(Request $request, $provider)
    {
      return \Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(Request $request, $provider)
    {
      try {
        $user = \Socialite::driver($provider)->user();
      }
      catch (\Exception $e) {
        return redirect("auth/login");
      }

      $authUser = $this->findOrCreateUser($user, $provider);

      \Auth::login($authUser);

      return \Redirect::to('/account');
    }

    private function findOrCreateUser($providerUser, $provider)
    {
      $providerIdField = "{$provider}_id";

      $authUser = User::where($providerIdField, $providerUser->getId())
                        ->orWhere('email', $providerUser->getEmail())
                        ->first();

      if ($authUser) {

        if (is_null($authUser->{$providerIdField})) {
          $authUser->update([$providerIdField => $providerUser->getId()]);
        }

        return $authUser;

      }

      return User::create([
        'name'            => $providerUser->getName(),
        'email'           => $providerUser->getEmail(),
        $providerIdField  => $providerUser->getId(),
        'avatar'          => $providerUser->getAvatar()
      ]);
    }
}
