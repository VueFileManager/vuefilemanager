<?php

namespace Support\Listeners;

use Illuminate\Events\Dispatcher;
use VueFileManager\Subscription\Support\Events\SubscriptionWasCreated;

class SubscriptionEventSubscriber
{
    public function handleSubscriptionWasCreated($event) {
        // TODO: set new storage size by subscribed plan
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