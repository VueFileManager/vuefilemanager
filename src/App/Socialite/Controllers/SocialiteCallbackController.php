<?php

namespace App\Socialite\Controllers;

use App\Users\Models\User;
use App\Users\DTO\CreateUserData;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Users\Actions\CreateNewUserAction;
use Illuminate\Contracts\Auth\StatefulGuard;

class SocialiteCallbackController extends Controller
{
    public function __construct(
        protected StatefulGuard $guard,
        public CreateNewUserAction $createNewUser,
    ) {
    }
    
    public function __invoke($provider)
    {
        // Get socialite user
        if (app()->runningInConsole()) {
            $provider_user = Socialite::driver($provider)->user();
        } else {
            $provider_user = Socialite::driver($provider)->stateless()->user();
        }

        // Check if user exist already
        $user = User::whereEmail($provider_user->email)->first();

        if($user) {
            // Login User
            $this->guard->login($user);

        } else {
           
            $data = CreateUserData::fromArray([
                'name'           => $provider_user->getname(),
                'email'          => $provider_user->getEmail(),
                'avatar'         => store_socialite_avatar($provider_user->getAvatar()),
                'oauth_provider' => $provider,
            ]);

            // Create User
            ($this->createNewUser)($data);
        }

        return response('Loged in', 200);
    }
}
