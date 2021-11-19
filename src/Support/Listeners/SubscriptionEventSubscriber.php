<?php
namespace Support\Listeners;

use Illuminate\Events\Dispatcher;
use VueFileManager\Subscription\Support\Events\SubscriptionWasCreated;
use VueFileManager\Subscription\Support\Events\SubscriptionWasExpired;
use VueFileManager\Subscription\Support\Events\SubscriptionWasUpdated;

class SubscriptionEventSubscriber
{
    public function handleSubscriptionWasCreated($subscription)
    {
        $subscription->user->settings->update([
            'max_storage_amount' => $subscription->feature('max_storage_amount'),
        ]);
    }

    public function handleSubscriptionWasUpdated($subscription)
    {
        $subscription->user->settings->update([
            'max_storage_amount' => $subscription->feature('max_storage_amount'),
        ]);
    }

    public function handleSubscriptionWasExpired($subscription)
    {
        $subscription->user->settings->update([
            'max_storage_amount' => get_settings('storage_default'),
        ]);
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            SubscriptionWasCreated::class => 'handleSubscriptionWasCreated',
            SubscriptionWasExpired::class => 'handleSubscriptionWasExpired',
            SubscriptionWasUpdated::class => 'handleSubscriptionWasUpdated',
        ];
    }
}
