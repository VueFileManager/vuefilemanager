<?php
namespace Support\Listeners;

use Illuminate\Events\Dispatcher;
use Domain\Subscriptions\Notifications\AdminBonusAddedNotification;
use VueFileManager\Subscription\Support\Events\AdminBonusAddedEvent;
use VueFileManager\Subscription\Support\Events\SubscriptionWasCreated;
use VueFileManager\Subscription\Support\Events\SubscriptionWasExpired;
use VueFileManager\Subscription\Support\Events\SubscriptionWasUpdated;
use Domain\Subscriptions\Notifications\InsufficientBalanceNotification;
use VueFileManager\Subscription\Support\Events\InsufficientBalanceEvent;
use Domain\Subscriptions\Notifications\BillingAlertTriggeredNotification;
use Domain\Subscriptions\Notifications\SubscriptionWasCreatedNotification;
use VueFileManager\Subscription\Support\Events\BillingAlertTriggeredEvent;

class SubscriptionEventSubscriber
{
    // TODO: unit test for notification
    public function handleSubscriptionWasCreated($event)
    {
        $event->subscription->user->limitations()->update([
            'max_storage_amount' => $event->subscription->fixedFeature('max_storage_amount'),
            'max_team_members'   => $event->subscription->fixedFeature('max_team_members'),
        ]);

        $event->subscription->user->notify(new SubscriptionWasCreatedNotification($event->subscription));
    }

    public function handleSubscriptionWasUpdated($event)
    {
        $event->subscription->user->limitations()->update([
            'max_storage_amount' => $event->subscription->fixedFeature('max_storage_amount'),
            'max_team_members'   => $event->subscription->fixedFeature('max_team_members'),
        ]);
    }

    public function handleSubscriptionWasExpired($event)
    {
        // TODO: set default team members
        $event->subscription->user->limitations()->update([
            'max_storage_amount' => get_settings('default_max_storage_amount') ?? 5,
            'max_team_members'   => 5,
        ]);
    }

    // TODO: unit test
    public function handleBillingAlertTriggeredEvent($event)
    {
        $event->alert->user->notify(new BillingAlertTriggeredNotification());
    }

    // TODO: unit test
    public function handleAdminBonusAddedEvent($event)
    {
        $event->user->notify(new AdminBonusAddedNotification($event->bonus));
    }

    // TODO: unit test
    public function handleInsufficientBalanceEvent($event)
    {
        $event->user->notify(new InsufficientBalanceNotification());
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            SubscriptionWasCreated::class     => 'handleSubscriptionWasCreated',
            SubscriptionWasExpired::class     => 'handleSubscriptionWasExpired',
            SubscriptionWasUpdated::class     => 'handleSubscriptionWasUpdated',
            BillingAlertTriggeredEvent::class => 'handleBillingAlertTriggeredEvent',
            AdminBonusAddedEvent::class       => 'handleAdminBonusAddedEvent',
            InsufficientBalanceEvent::class   => 'handleInsufficientBalanceEvent',
        ];
    }
}
