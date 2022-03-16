<?php
namespace Domain\Subscriptions\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BillingAlertTriggeredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Your billing alert has been reached!'))
            ->greeting(__('Hi there'))
            ->line(__('The billing alert you set previously has been reached. Please go to your user account and revise your spending'))
            ->action(__('Show Billing'), url('/user/settings/billing'));
    }

    public function toArray(): array
    {
        return [
            'category'    => 'billing-alert',
            'title'       => 'billing Alert Reached!',
            'description' => "The billing alert you set previously has been reached. Please revise your spending.",
            'action'      => [
                'type'   => 'route',
                'params' => [
                    'route'  => 'Billing',
                    'button' => 'Show Billing',
                ],
            ],
        ];
    }
}
