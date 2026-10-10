<?php
namespace VueFileManager\Subscription\Domain\BillingAlerts\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BillingAlertTriggeredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Your billing alert has been reached!'))
            ->greeting(__('Hi there'))
            ->line(__('The billing alert you set previously has been reached. Please go to your user account and revise your spending'))
            ->action(__('Show Billing'), url('/user/settings/billing'));
    }
}
