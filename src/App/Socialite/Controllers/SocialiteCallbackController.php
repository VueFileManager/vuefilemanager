<?php
namespace App\Socialite\Controllers;

use App\Users\Models\User;
use App\Users\DTO\CreateUserData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use App\Users\Actions\CreateNewUserAction;
use Illuminate\Contracts\Auth\StatefulGuard;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\Plans\Exceptions\MeteredBillingPlanDoesntExist;

class SocialiteCallbackController extends Controller
{
    public function __construct(
        protected StatefulGuard $guard,
        public CreateNewUserAction $createNewUser,
    ) {
    }

    /**
     * @throws MeteredBillingPlanDoesntExist
     */
    public function __invoke(
        $provider
    ): JsonResponse|RedirectResponse {
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

        // Check for metered billing plan
        if (get_settings('subscription_type') === 'metered') {
            // Get metered plan
            $plan = Plan::where('status', 'active')
                ->where('type', 'metered');

            // TODO: redirect to the error page
            if ($plan->doesntExist()) {
                return response()->json([
                    'type'    => 'error',
                    'message' => 'User registrations are temporarily disabled',
                ], 409);
            }
        }

        // Check if account registration is enabled
        if (! $isAllowedRegistration) {
            return response()->json([
                'type'    => 'error',
                'message' => 'User registration is not allowed',
            ], 401);
        }

        // Create data user data object
        $data = CreateUserData::fromArray([
            'role'           => 'user',
            'name'           => $socialite->getName(),
            'email'          => $socialite->getEmail(),
            'avatar'         => store_socialite_avatar($socialite->getAvatar()),
            'oauth_provider' => $provider,
        ]);

        // Create User
        $newUser = ($this->createNewUser)($data);

        // Login user
        $this->guard->login($newUser);

        return redirect()->to('/platform/files');
    }
}
