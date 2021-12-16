<?php
namespace App\Users\Controllers\Authentication;

use App\Users\Models\User;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Users\Actions\CreateNewUserAction;
use Illuminate\Contracts\Auth\StatefulGuard;

class SocialiteAuthenticationController extends Controller
{
    public function __construct(
        protected StatefulGuard $guard,
        public CreateNewUserAction $createNewUser,
    ) {
    }
    
    public function redirect($provider)
    {
       $url = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();

       return response()->json([
           'url' => $url
       ]);
    }

    public function callback($provider)
    {
        // Get socialite user
        $provider_user = Socialite::driver($provider)->stateless()->user();

        // Check if user exist already
        $user = User::whereEmail($provider_user->email)->first();        

        if($user) {
            // Login User
            $this->guard->login($user);

        } else {
            // Add user avatar from socialite
            $provider_user->avatar = get_socialite_avatar($provider_user->avatar);

            // Create User
            ($this->createNewUser)($provider_user);
        }

        return response('Loged in', 200);
    }
}   
