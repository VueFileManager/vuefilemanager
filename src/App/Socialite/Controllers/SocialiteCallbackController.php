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
        if (app()->runningUnitTests()) {
            $provider_user = Socialite::driver($provider)->user();
        } else {
            $provider_user = Socialite::driver($provider)->stateless()->user();
        }

        // Check if user exist already
        $user = User::where('email', $provider_user->email)->first();

        // Login User
        if ($user) {
            $this->guard->login($user);

            return response('User logged in', 201);
        }

        // Create data user data object
        $data = CreateUserData::fromArray([
            'name'           => $provider_user->name,
            'email'          => $provider_user->email,
            'avatar'         => store_socialite_avatar($provider_user->avatar),
            'oauth_provider' => $provider,
        ]);

        // Create User
        ($this->createNewUser)($data);

        return response('User registered', 201);
    }
}
