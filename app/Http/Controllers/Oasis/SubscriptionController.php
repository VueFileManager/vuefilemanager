<?php

namespace App\Http\Controllers\Oasis;

use App\Http\Controllers\Controller;
use App\Http\Resources\Oasis\SubscriptionRequestResource;
use App\Http\Resources\PlanResource;
use App\Models\Oasis\SubscriptionRequest;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function get_subscription_request(SubscriptionRequest $order)
    {
        return response(
            new SubscriptionRequestResource($order), 200
        );
    }

    /**
     * Get setup intent to register credit card
     *
     * @param SubscriptionRequest $order
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function subscribe(Request $request, SubscriptionRequest $order)
    {
        // Create subscription
        $order->user
            ->newSubscription('main', $order->requested_plan)
            ->create(
                $this->stripe->getOrSetDefaultPaymentMethod($request, $order->user)
            );

        // Get requested plan
        $plan = $this->stripe
            ->getPlan($order->requested_plan);

        // Update Subscription request
        $order->update([
            'status' => 'payed'
        ]);

        // Update user storage limit
        $order->user
            ->settings()
            ->update([
                'storage_capacity'   => $plan['product']['metadata']['capacity'],
                'payment_activation' => 1,
            ]);

        return response('Done!', 204);
    }
}
