<?php
namespace App\Users\Actions;

use App\Users\Models\User;
use App\Users\DTO\CreateUserData;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\StatefulGuard;

class CreateNewUserAction extends Controller
{
    public function __construct(
        protected StatefulGuard $guard,
        protected AutoSubscribeForMeteredBillingAction $autoSubscribeForMeteredBilling,
    ) {
    }

    /**
     * Validate and create a new user.
     */
    public function __invoke(CreateUserData $data)
    {
        $settings = get_settings([
            'user_verification', 'subscription_type',
        ]);

        // Create user
        $user = User::create([
            'password'       => $data->password ? bcrypt($data->password) : null,
            'oauth_provider' => $data->oauth_provider,
            'email'          => $data->email,
        ]);

        // Split username
        $name = split_name($data->name);

        // Store user data
        $user->settings()->create([
            'first_name' => $name['first_name'],
            'last_name'  => $name['last_name'],
            'avatar'     => $data->avatar,
        ]);

        // Subscribe user for metered billing
        if ($settings['subscription_type'] === 'metered') {
            ($this->autoSubscribeForMeteredBilling)($user);
        }

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
