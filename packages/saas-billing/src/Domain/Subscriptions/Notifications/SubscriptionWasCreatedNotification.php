<?php
namespace VueFileManager\Subscription\Domain\Subscriptions\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;

class SubscriptionWasCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Subscription $subscription,
    ) {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__("Your subscription {$this->subscription->plan->name} has been successfully created"))
            ->greeting(__('Hi there'))
            ->line(__("You have been successfully subscribed to your {$this->subscription->plan->name} subscription. Now you can take full advantage of our platform."))
            ->action(__('Go to Subscription'), url('/user/settings/billing'));
    }
}
