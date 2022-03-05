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
        $isAllowedRegistration = intval(get_settings('registration'));

        // Get socialite user
        if (app()->runningUnitTests()) {
            $socialite = Socialite::driver($provider)->user();
        } else {
            $socialite = Socialite::driver($provider)->stateless()->user();
        }

        // Get user by email
        $user = User::where('email', $socialite->email);

        // Login user when exists
        if ($user->exists()) {
            $this->guard->login(
                $user->first()
            );

            return redirect()->to('/platform/files');
        }

        // Check if account registration is enabled
        if (! $isAllowedRegistration) {
            return response([
                'type'    => 'error',
                'message' => 'User registration is not allowed',
            ], 401);
        }

        // Create data user data object
        $data = CreateUserData::fromArray([
            'name'           => $socialite->getName(),
            'email'          => $socialite->getEmail(),
            'avatar'         => store_socialite_avatar($socialite->getAvatar()),
            'oauth_provider' => $provider,
        ]);

        // Create User
        $user = ($this->createNewUser)($data);

        // Login user
        $this->guard->login($user->first());

        return redirect()->to('/platform/files');
    }
}
