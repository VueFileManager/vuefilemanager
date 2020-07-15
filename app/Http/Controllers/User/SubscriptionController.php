<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\StoreUpgradeAccountRequest;
use App\Http\Resources\UserSubscription;
use App\Http\Tools\Demo;
use App\Invoice;
use App\Services\StripeService;
use Auth;
use Cartalyst\Stripe\Exception\CardErrorException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SubscriptionController extends Controller
{
    private $stripe;

    /**
     * SubscriptionController constructor.
     * @param $payment
     */
    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
    }

    /**
     * Generate setup intent
     *
     * @return \Stripe\SetupIntent
     */
    public function stripe_setup_intent()
    {
        $user = Auth::user();

        return $this->stripe->getSetupIntent($user);
    }

    /**
     * Get user subscription detail
     *
     * @return array
     */
    public function show()
    {
        $user = Auth::user();

        if (! $user->subscription('main')) {
            return abort(204, 'User don\'t have any subscription');
        }

        $slug_user_subscription = 'subscription-user-' . $user->id;

        if (Cache::has($slug_user_subscription)) {
            return Cache::get($slug_user_subscription);
        }

        return Cache::rememberForever($slug_user_subscription, function () {
            return new UserSubscription(
                Auth::user()
            );
        });
    }

    /**
     * Upgrade account to subscription
     *
     * @param StoreUpgradeAccountRequest $request
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function upgrade(StoreUpgradeAccountRequest $request)
    {
        // Get user
        $user = Auth::user();

        // Check if is demo
        if (is_demo($user->id)) {
            return Demo::response_204();
        }

        // Forget user subscription
        Cache::forget('subscription-user-' . $user->id);

        // Get requested plan
        $plan = $this->stripe->getPlan($request->input('plan.data.id'));

        // Set user billing
        $user->setBilling($request->input('billing'));

        // Update stripe customer billing info
        $this->stripe->updateCustomerDetails($user);

        // Make subscription
        $this->stripe->createOrReplaceSubscription($request, $user);

        // Update user storage limit
        $user->settings()->update([
            'storage_capacity' => $plan['product']['metadata']['capacity']
        ]);

        return response('Done!', 204);
    }

    /**
     * Cancel Subscription
     *
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function cancel()
    {
        $user = Auth::user();

        // Check if is demo
        if (is_demo($user->id)) {
            return Demo::response_204();
        }

        // Cancel subscription
        $user->subscription('main')->cancel();

        // Forget user subscription
        Cache::forget('subscription-user-' . $user->id);

        return response('Done!', 204);
    }

    /**
     * Resume Subscription
     *
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function resume()
    {
        $user = Auth::user();

        // Check if is demo
        if (is_demo($user->id)) {
            return Demo::response_204();
        }

        // Resume subscription
        $user->subscription('main')->resume();

        // Forget user subscription
        Cache::forget('subscription-user-' . $user->id);

        return response('Done!', 204);
    }
}
