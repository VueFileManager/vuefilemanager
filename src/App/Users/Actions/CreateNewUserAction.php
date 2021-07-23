<?php
namespace App\Users\Actions;

use App\Users\Models\User;
use Illuminate\Http\Response;
use App\Users\Models\UserSettings;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use App\Users\Requests\RegisterUserRequest;
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
    public function __invoke(
        RegisterUserRequest $request
    ): Application | ResponseFactory | Response {
        $settings = get_settings([
            'storage_default', 'registration', 'user_verification',
        ]);

        // Check if account registration is enabled
        if (! intval($settings['registration'])) {
            abort(401);
        }

        // Create user
        $user = User::create([
            'password' => bcrypt($request->input('password')),
            'email'    => $request->input('email'),
        ]);

        // Mark as verified if verification is disabled
        if (! intval($settings['user_verification'])) {
            $user->markEmailAsVerified();
        }

        UserSettings::unguard();

        $user
            ->settings()
            ->create([
                'name'             => $request->input('name'),
                'storage_capacity' => $settings['storage_default'],
            ]);

        UserSettings::reguard();

        event(new Registered($user));

        // Log in if verification is disabled
        if (! intval($settings['user_verification'])) {
            $this->guard->login($user);
        }

        return response('User registered successfully', 201);
    }
}
