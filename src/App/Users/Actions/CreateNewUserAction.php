<?php
namespace App\Users\Actions;

use App\Users\Models\User;
use Illuminate\Http\Response;
use App\Users\Models\UserSettings;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;

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
            'storage_default', 'registration', 'user_verification',
        ]);

        $is_socialite = is_null($data->password);

        // Check if account registration is enabled
        if (! intval($settings['registration'])) {
            abort(401);
        }

        // Create user
        $user = User::create([
            'password'       => $is_socialite ? null : bcrypt($data->password),
            'oauth_provider' => $data->oauth_provider,
            'email'          => $data->email,
        ]);

        UserSettings::unguard();

        $user
            ->settings()
            ->create([
                'name'             => $data->name,
                'storage_capacity' => $settings['storage_default'],
                'avatar'           => $data->avatar,
            ]);

        UserSettings::reguard();

        // Mark as verified if verification is disabled
        if ($is_socialite || ! intval($settings['user_verification'])) {
            $user->markEmailAsVerified();
        }

        event(new Registered($user));

        // Log in if verification is disabled
        if ($is_socialite || ! intval($settings['user_verification'])) {
            $this->guard->login($user);
        }
    }
}
