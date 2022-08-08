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
    public function __construct(
        protected AutoSubscribeForMeteredBillingAction $autoSubscribeForMeteredBilling,
    ) {
    }

    /**
     * Validate and create a new user.
     *
     * @throws MeteredBillingPlanDoesntExist
     */
    public function __invoke(CreateUserData $data): User
    {
        // Check if subscription metered billing plan exist
        $this->checkMeteredBillingPlan($data);

        // Create user
        $user = $this->createUser($data);

        // Join to previously accepted team folder invitations
        $this->applyExistingTeamInvitations($user);

        // Subscribe user for metered billing
        if (get_settings('subscription_type') === 'metered' && $data->role !== 'admin') {
            ($this->autoSubscribeForMeteredBilling)($user);
        }

        // Mark as verified if verification is disabled
        if (! $data->password || ! intval(get_settings('user_verification'))) {
            $user->markEmailAsVerified();
        }

        return $user;
    }

    /**
     * @throws MeteredBillingPlanDoesntExist
     */
    private function checkMeteredBillingPlan(CreateUserData $data): void
    {
        if (get_settings('subscription_type') === 'metered' && $data->role !== 'admin') {
            // Get metered plan
            $plan = Plan::where('status', 'active')
                ->where('type', 'metered');

            if ($plan->doesntExist()) {
                throw new MeteredBillingPlanDoesntExist();
            }
        }
    }

    private function applyExistingTeamInvitations(User $user): void
    {
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
    }

    private function createUser(CreateUserData $data): User
    {
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

        return $user;
    }
}
