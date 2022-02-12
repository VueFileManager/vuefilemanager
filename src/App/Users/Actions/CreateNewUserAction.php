<?php

namespace App\Users\Actions;

use App\Users\Models\User;
use App\Users\DTO\CreateUserData;
use App\Http\Controllers\Controller;
use Domain\Teams\Models\TeamFolderInvitation;
use Domain\Teams\Models\TeamFolderMember;
use Illuminate\Auth\Events\Registered;

class CreateNewUserAction extends Controller
{
    public function __construct(
        protected AutoSubscribeForMeteredBillingAction $autoSubscribeForMeteredBilling,
    ) {}

    /**
     * Validate and create a new user.
     */
    public function __invoke(CreateUserData $data): User
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

        // Join to previously accepted team folder invitations
        TeamFolderInvitation::where('email', $user->email)
            ->where('status', 'waiting-for-registration')
            ->cursor()
            ->each(function ($invitation) use ($user) {
                TeamFolderMember::create([
                    'user_id'    => $user->id,
                    'parent_id'  => $invitation->parent_id,
                    'permission' => $invitation->permission,
                ]);

                $invitation->accept();
            });

        // Subscribe user for metered billing
        if ($settings['subscription_type'] === 'metered') {
            ($this->autoSubscribeForMeteredBilling)($user);
        }

        // Mark as verified if verification is disabled
        if (!$data->password || !intval($settings['user_verification'])) {
            $user->markEmailAsVerified();
        }

        event(new Registered($user));

        return $user;
    }
}
