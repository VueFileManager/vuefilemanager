<?php
namespace VueFileManager\Subscription\Support\Webhooks;

use Carbon\Carbon;
use Illuminate\Http\Request;
use VueFileManager\Subscription\Support\EngineManager;
use VueFileManager\Subscription\Domain\Plans\Models\PlanDriver;
use VueFileManager\Subscription\Domain\Customers\Models\Customer;
use VueFileManager\Subscription\Support\Events\SubscriptionWasCreated;
use VueFileManager\Subscription\Support\Events\SubscriptionWasExpired;
use VueFileManager\Subscription\Support\Events\SubscriptionWasUpdated;
use VueFileManager\Subscription\Support\Events\SubscriptionWasCancelled;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;
use VueFileManager\Subscription\Domain\Subscriptions\Models\SubscriptionDriver;
use VueFileManager\Subscription\Domain\FailedPayments\Actions\RetryChargeFromPaymentCardAction;

trait StripeWebhooks
{
    public function handleCustomerSubscriptionCreated(Request $request): void
    {
        $customerCode = $request->input('data.object.customer');
        $subscriptionCode = $request->input('data.object.id');
        $planCode = $request->input('data.object.plan.id');

        // Get existing customer
        $customer = Customer::where('driver', 'stripe')
            ->where('driver_user_id', $customerCode)
            ->first();

        // Proceed as fixed subscription
        $planDriver = PlanDriver::where('driver_plan_id', $planCode)
            ->first();

        $subscription = Subscription::create([
            'type'    => 'fixed',
            'status'  => 'inactive',
            'plan_id' => $planDriver->plan->id,
            'user_id' => $customer->user_id,
            'name'    => $planDriver->plan->name,
        ]);

        // Store subscription pivot to gateway
        $subscription
            ->driver()
            ->create([
                'driver'                 => 'stripe',
                'driver_subscription_id' => $subscriptionCode,
            ]);
    }

    public function handleCustomerSubscriptionUpdated(Request $request): void
    {
        $subscriptionCode = $request->input('data.object.id');
        $currentPeriodEndsAt = $request->input('data.object.current_period_end');
        $cancelAtPeriodEnd = $request->input('data.object.cancel_at_period_end');
        $planCode = $request->input('data.object.plan.id');
        $status = $request->input('data.object.status');

        $driver = SubscriptionDriver::where('driver_subscription_id', $subscriptionCode)
            ->first();

        // Update Status
        if ($driver->subscription->status === 'inactive' && $status === 'active') {
            $driver->subscription->update([
                'status' => mapStripeStatus($status),
            ]);

            $driver->subscription->refresh();

            // Get notification
            $SubscriptionWasCreatedNotification = config('subscription.notifications.SubscriptionWasCreatedNotification');

            // Notify user
            $driver->subscription->user->notify(new $SubscriptionWasCreatedNotification($driver->subscription));

            // Emit SubscriptionWasCreated
            SubscriptionWasCreated::dispatch($driver->subscription);
        }

        // Plan cancellation
        if ($cancelAtPeriodEnd) {
            $driver->subscription->update([
                'status'  => 'cancelled',
                'ends_at' => Carbon::createFromTimestamp($currentPeriodEndsAt),
            ]);

            $driver->subscription->refresh();

            SubscriptionWasCancelled::dispatch($driver->subscription);
        }

        // Plan payment expired
        if ($status === 'incomplete_expired') {
            $driver->subscription->update([
                'status'  => 'completed',
                'ends_at' => now(),
            ]);

            $driver->subscription->refresh();

            SubscriptionWasExpired::dispatch($driver->subscription);
        }

        // Swap plan subscription
        if ($driver->subscription->plan->driverId('stripe') !== $planCode) {
            if ($driver->subscription->active() || $driver->subscription->onGracePeriod()) {
                $planDriver = PlanDriver::where('driver_plan_id', $request->input('data.object.plan.id'))
                    ->first();

                $driver->subscription->update([
                    'name'    => $planDriver->plan->name,
                    'plan_id' => $planDriver->plan->id,
                ]);

                $driver->subscription->refresh();

                SubscriptionWasUpdated::dispatch($driver->subscription);
            }
        }
    }

    public function handleCustomerSubscriptionDeleted(Request $request): void
    {
        $subscriptionCode = $request->input('data.object.id');

        $driver = SubscriptionDriver::where('driver_subscription_id', $subscriptionCode)
            ->first();

        if ($driver->subscription->active() || $driver->subscription->onGracePeriod()) {
            $driver->subscription->update([
                'status'  => 'cancelled',
                'ends_at' => Carbon::createFromTimestamp($request->input('data.object.current_period_end')),
            ]);

            $driver->subscription->refresh();

            SubscriptionWasCancelled::dispatch($driver->subscription);
        }
    }

    public function handleInvoicePaymentSucceeded(Request $request): void
    {
        $subscriptionCode = $request->input('data.object.subscription');
        $customerCode = $request->input('data.object.customer');

        $customer = Customer::where('driver_user_id', $customerCode)
            ->first();

        // Get subscription from database
        $subscription = SubscriptionDriver::where('driver_subscription_id', $subscriptionCode)
            ->first()
            ->subscription;

        // Get subscription from stripe api
        $remoteSubscription = resolve(EngineManager::class)
            ->driver('stripe')
            ->getSubscription($subscriptionCode)
            ->json();

        // Update subscription renews at attribute
        $subscription->update([
            'renews_at' => Carbon::parse($remoteSubscription['current_period_end']),
        ]);

        $customer->user->transactions()->create([
            'status'    => 'completed',
            'type'      => 'charge',
            'driver'    => 'stripe',
            'note'      => $subscription->name,
            'reference' => $request->input('data.object.id'),
            'currency'  => $request->input('data.object.currency'),
            'amount'    => $request->input('data.object.amount_paid') / 100,
        ]);
    }

    public function handleInvoicePaymentActionRequired(Request $request): void
    {
        // Get Stripe customer
        $customer = Customer::where('driver_user_id', $request->input('data.object.customer'))
            ->first();

        // Get notification
        $ConfirmStripePaymentNotification = config('subscription.notifications.ConfirmStripePaymentNotification');

        // Send notification
        $customer->user->notify(new $ConfirmStripePaymentNotification([
            'url'    => $request->input('data.object.hosted_invoice_url'),
            'amount' => format_currency(
                amount: $request->input('data.object.amount_remaining') / 100,
                currency: $request->input('data.object.currency')
            ),
        ]));
    }

    public function handlePaymentMethodAttached(Request $request): void
    {
        // Get Stripe customer
        $customer = Customer::where('driver_user_id', $request->input('data.object.customer'))
            ->first();

        // Parse card expiration date
        $expirationDate = Carbon::parse($request->input('data.object.card.exp_year') . '-' . $request->input('data.object.card.exp_month') . '-01');

        // Create credit card
        $customer->user->creditCards()->create([
            'brand'      => $request->input('data.object.card.brand'),
            'last4'      => $request->input('data.object.card.last4'),
            'reference'  => $request->input('data.object.id'),
            'expiration' => $expirationDate,
            'service'    => 'stripe',
        ]);

        // Check if user has failed payments, if yes, retry charge
        if ($customer->user->failedPayments()->exists()) {
            resolve(RetryChargeFromPaymentCardAction::class)($customer->user);
        }

        // Remove dunning warning
        $customer->user->dunning?->delete();

        // Delete cached dunning count
        cache()->forget("dunning-count.{$customer->user->id}");
    }

    /**
     * Handle offline charge for native metered billing system
     */
    public function handlePaymentIntentSucceeded(Request $request)
    {
        // TODO: implement
    }
}
