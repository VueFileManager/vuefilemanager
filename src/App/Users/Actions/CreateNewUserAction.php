<?php
namespace App\Users\Actions;

use App\Users\Models\User;
use App\Users\DTO\CreateUserData;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Domain\Teams\Models\TeamFolderMember;
use Domain\Teams\Models\TeamFolderInvitation;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\Plans\Exceptions\MeteredBillingPlanDoesntExist;

class CreateNewUserAction extends Controller
{
    /**
     * Validate and create a new user.
     */
    public function __invoke(CreateUserData $data): User
    {
        // Create user
        $user = $this->createUser($data);

        // Mark as verified if verification is disabled
        if (! $data->password || ! intval(get_settings('user_verification'))) {
            $user->markEmailAsVerified();
        }

        event(new Registered($user));

        return $user;
    }

    private function createUser(CreateUserData $data): User
    {
        $user = User::create([
            'password'       => $data->password ? bcrypt($data->password) : null,
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

        return $user;
    }
}
