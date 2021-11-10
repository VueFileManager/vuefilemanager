<?php

namespace Support\Listeners;

use Illuminate\Events\Dispatcher;
use VueFileManager\Subscription\Support\Events\SubscriptionWasCreated;

class SubscriptionEventSubscriber
{
    public function handleSubscriptionWasCreated($subscription) {

        // Get plan features
        $features = $subscription->plan->features()->pluck('value', 'key');

        // Set user storage size
        $subscription->user->settings->update([
            'storage_capacity' => $features['max_storage_amount']
        ]);
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            SubscriptionWasCreated::class => 'handleSubscriptionWasCreated',
        ];
    }
}