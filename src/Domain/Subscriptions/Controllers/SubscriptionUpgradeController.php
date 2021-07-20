<?php


namespace Domain\Subscriptions\Controllers;


use App\Http\Controllers\Controller;
use Auth;
use Domain\Subscriptions\Requests\StoreUpgradeAccountRequest;
use Domain\Subscriptions\Services\StripeService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

/**
 * Upgrade account to subscription
 */
class SubscriptionUpgradeController extends Controller
{
    public function __construct(
        public StripeService $stripe,
    ) {}

    public function __invoke(StoreUpgradeAccountRequest $request): Response
    {
        $user = Auth::user();

        // Check if is demo
        if (is_demo_account($user->email)) {
            return response('Done!', 204);
        }

        // Forget user subscription
        Cache::forget("subscription-user-{$user->id}");

        // Get requested plan
        $plan = $this->stripe->getPlan(
            $request->input('plan.data.id')
        );

        // Set user billing
        $user->setBilling(
            $request->input('billing')
        );

        // Update stripe customer billing info
        $this->stripe->updateCustomerDetails($user);

        // Make subscription
        $this->stripe->createOrReplaceSubscription($request, $user);

        // Update user storage limit
        $user
            ->settings()
            ->update([
                'storage_capacity' => $plan['product']['metadata']['capacity'],
            ]);

        return response('Done!', 204);
    }
}