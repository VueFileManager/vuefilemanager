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
            ->subject(__t('billing_alert_reached_long'))
            ->greeting(__t('hello'))
            ->line(__t('billing_alert_reached_long_note'))
            ->action(__t('show_billing'), url('/user/settings/billing'));
    }

    public function toArray(): array
    {
        return [
            'category'    => 'billing-alert',
            'title'       => __t('billing_alert_reached_short'),
            'description' => __t('billing_alert_reached_short_note'),
            'action'      => [
                'type'   => 'route',
                'params' => [
                    'route'  => __t('billing'),
                    'button' => __t('show_billing'),
                ],
            ],
        ];
    }
}
