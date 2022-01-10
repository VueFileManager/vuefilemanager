<?php
namespace App\Users\Actions;

use App\Users\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\StatefulGuard;

class CreateNewUserAction extends Controller
{
    public function __construct(
        protected StatefulGuard $guard
    ) {
    }

    /**
     * Validate and create a new user.
     */
    public function __invoke($data)
    {
        $settings = get_settings([
            'registration', 'user_verification',
        ]);

        // Check if account registration is enabled
        if (! intval($settings['registration'])) {
            abort(401);
        }

        // Create user
        $user = User::create([
            'password'       => $data->password ? bcrypt($data->password) : null,
            'oauth_provider' => $data->oauth_provider,
            'email'          => $data->email,
        ]);

        $user
            ->settings()
            ->create([
                'name'   => $data->name,
                'avatar' => $data->avatar,
            ]);

        // Mark as verified if verification is disabled
        if (! $data->password || ! intval($settings['user_verification'])) {
            $user->markEmailAsVerified();
        }

        event(new Registered($user));

        // Log in if verification is disabled
        if (! $data->password || ! intval($settings['user_verification'])) {
            $this->guard->login($user);
        }
    }
}
