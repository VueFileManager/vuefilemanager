<?php
namespace App\Http\Controllers\User;

use Auth;
use App\Models\User;
use Stripe\SetupIntent;
use App\Services\DemoService;
use Illuminate\Http\Response;
use App\Services\StripeService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\UserSubscription;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Requests\Subscription\StoreUpgradeAccountRequest;

class SubscriptionController extends Controller
{
    private $stripe;
    private $demo;

    public function __construct()
    {
        $this->stripe = resolve(StripeService::class);
        $this->demo = DemoService::class;
    }

    /**
     * Generate setup intent
     *
     * @return Application|ResponseFactory|Response|SetupIntent
     */
    public function setup_intent()
    {
        return response(
            $this->stripe->getSetupIntent(Auth::user()),
            201
        );
    }

    /**
     * Get user subscription detail
     *
     * @return void
     */
    public function show()
    {
        $user = User::find(Auth::id());

        if (! $user->subscription('main')) {
            return abort(204, 'User don\'t have any subscription');
        }

        $slug = 'subscription-user-' . $user->id;

        if (Cache::has($slug)) {
            return Cache::get($slug);
        }

        return Cache::rememberForever($slug, function () use ($user) {
            return new UserSubscription(
                $user
            );
        });
    }

    /**
     * Upgrade account to subscription
     *
     * @param StoreUpgradeAccountRequest $request
     * @return ResponseFactory|Response
     */
    public function upgrade(StoreUpgradeAccountRequest $request)
    {
        // Get user
        $user = Auth::user();

        // Check if is demo
        if (is_demo($user->id)) {
            return $this->demo->response_204();
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
            'storage_capacity' => $plan['product']['metadata']['capacity'],
        ]);

        return response('Done!', 204);
    }

    /**
     * Cancel Subscription
     *
     * @return ResponseFactory|Response
     */
    public function cancel()
    {
        $user = User::find(Auth::id());

        // Check if is demo
        if (is_demo($user->id)) {
            return $this->demo->response_204();
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
     * @return ResponseFactory|Response
     */
    public function resume()
    {
        $user = User::find(Auth::id());

        // Check if is demo
        if (is_demo($user->id)) {
            return $this->demo->response_204();
        }

        // Resume subscription
        $user->subscription('main')->resume();

        // Forget user subscription
        Cache::forget('subscription-user-' . $user->id);

        return response('Done!', 204);
    }
}
