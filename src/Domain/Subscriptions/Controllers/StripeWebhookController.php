<?php
namespace Domain\Subscriptions\Controllers;

use App\Users\Models\User;
use Domain\Subscriptions\Services\StripeService;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class StripeWebhookController extends CashierController
{
    public function __construct(
        public StripeService $stripe
    ) {
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
        $user = User::whereStripeId($payload['data']['object']['customer'])
            ->firstOrFail();

        // Update storage capacity
        $user
            ->settings()
            ->update([
                'storage_capacity' => get_setting('storage_default'),
            ]);

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
        $user = User::whereStripeId($payload['data']['object']['customer'])
            ->firstOrFail();

        // Get requested plan
        $plan = $this->stripe->getPlan($user->subscription('main')->stripe_plan);

        // Update user storage limit
        $user
            ->settings()
            ->update([
                'storage_capacity' => $plan['product']['metadata']['capacity'],
            ]);

        return $this->successMethod();
    }
}
