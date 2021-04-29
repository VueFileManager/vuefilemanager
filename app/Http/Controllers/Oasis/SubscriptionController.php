<?php
namespace App\Http\Controllers\Oasis;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\StripeService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Oasis\SubscriptionRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Requests\User\UpdateUserPasswordRequest;
use App\Http\Resources\Oasis\SubscriptionRequestResource;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->stripe = resolve(StripeService::class);
    }

    /**
     * Get subscription request details
     *
     * @param SubscriptionRequest $order
     * @return Application|ResponseFactory|Response
     */
    public function get_subscription_request(SubscriptionRequest $order)
    {
        return response(
            new SubscriptionRequestResource($order),
            200
        );
    }

    /**
     * Get setup intent to register credit card
     *
     * @param SubscriptionRequest $order
     * @return Application|ResponseFactory|Response
     */
    public function get_setup_intent(SubscriptionRequest $order)
    {
        // Create stripe customer if not exist
        $order->user->createOrGetStripeCustomer();

        // Return setup intent
        return response($order->user->createSetupIntent(), 201);
    }

    /**
     * Subscribe user
     *
     * @param Request $request
     * @param SubscriptionRequest $order
     * @return Application|ResponseFactory|Response
     */
    public function subscribe(Request $request, SubscriptionRequest $order)
    {
        // Make subscription from subscription request
        if ($order->exists) {
            // Create subscription
            $order->user
                ->newSubscription('main', $order->requested_plan)
                ->create(
                    $this->stripe->getOrSetDefaultPaymentMethod($request, $order->user)
                );

            // Update Subscription request
            $order->update(['status' => 'payed']);

            $user = $order->user;
        }

        // Make subscription after user sign up and pay for the plan
        if (! $order->exists) {
            $user = Auth::user();

            // Set user billing
            $user->setBilling($request->billing);

            // Update stripe customer billing info
            $this->stripe->updateCustomerDetails($user);

            // Make subscription
            $this->stripe->createOrReplaceSubscription($request, $user);
        }

        // Get plan
        $plan = $this->stripe->getPlan(
            $order->requested_plan ?? $request->input('plan.data.id')
        );

        // Update user storage limit
        $user
            ->settings()
            ->update([
                'storage_capacity' => $plan['product']['metadata']['capacity'],
                'payment_activation' => 1,
            ]);

        return response('Done!', 204);
    }

    /**
     * Set user password
     *
     * @param UpdateUserPasswordRequest $request
     * @param SubscriptionRequest $order
     * @return Application|ResponseFactory|Response
     */
    public function set_password(UpdateUserPasswordRequest $request, SubscriptionRequest $order)
    {
        // Check unauthorized action
        if ($order->status !== 'payed') {
            abort(401, "Sorry, you don't have permission.");
        }

        // Set user password
        $order->user->password = Hash::make($request->password);
        $order->user->save();

        // Update status
        $order->update([
            'status' => 'logged',
        ]);

        // Log in user
        Auth::login($order->user);
        $request->session()->regenerate();

        return response('Password was set.', 204);
    }
}
