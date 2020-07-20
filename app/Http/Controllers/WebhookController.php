<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use App\Setting;
use App\User;
use Illuminate\Http\Request;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class WebhookController extends CashierController
{
    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
    }

    /**
     * Handle a cancelled customer from a Stripe subscription.
     *
     * @param array $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleCustomerSubscriptionDeleted($payload)
    {
        if ($user = $this->getUserByStripeId($payload['data']['object']['customer'])) {
            $user->subscriptions->filter(function ($subscription) use ($payload) {
                return $subscription->stripe_id === $payload['data']['object']['id'];
            })->each(function ($subscription) {
                $subscription->markAsCancelled();
            });
        }

        // Get user
        $user = User::where('stripe_id', $payload['data']['object']['customer'])->firstOrFail();

        // Get default storage capacity
        $default_storage = Setting::where('name', 'storage_default')->first();

        // Update storage capacity
        $user->settings()->update(['storage_capacity' => $default_storage->value]);

        return $this->successMethod();
    }

    /**
     * Handle Invoice Payment Succeeded
     *
     * @param $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleInvoicePaymentSucceeded($payload)
    {
        // Get user
        $user = User::where('stripe_id', $payload['data']['object']['customer'])->firstOrFail();

        // Get requested plan
        $plan = $this->stripe->getPlan($user->subscription('main')->stripe_plan);

        // Update user storage limit
        $user->settings()->update([
            'storage_capacity' => $plan['product']['metadata']['capacity']
        ]);

        return $this->successMethod();
    }
}
