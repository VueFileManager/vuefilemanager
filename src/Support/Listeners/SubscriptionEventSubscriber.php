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
        $subscription->user->limitations()->update([
            'max_storage_amount' => $subscription->feature('max_storage_amount'),
            'max_team_members'   => $subscription->feature('max_team_members'),
        ]);
    }

    public function handleSubscriptionWasUpdated($subscription)
    {
        $subscription->user->limitations()->update([
            'max_storage_amount' => $subscription->feature('max_storage_amount'),
            'max_team_members'   => $subscription->feature('max_team_members'),
        ]);
    }

    public function handleSubscriptionWasExpired($subscription)
    {
        $subscription->user->limitations()->update([
            'max_storage_amount' => get_settings('default_storage_amount'),
            'max_team_members'   => 5,
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
