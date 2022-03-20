<?php
namespace Domain\Subscriptions\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InsufficientBalanceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__t('withdrawal_failed_long'))
            ->greeting(__t('hello'))
            ->line(__t('withdrawal_failed_long_note'))
            ->action(__t('fund_your_account'), url('/user/settings/billing'));
    }

    public function toArray(): array
    {
        return [
            'category'    => 'insufficient-balance',
            'title'       => __t('withdrawal_failed_short'),
            'description' => __t('withdrawal_failed_short_note'),
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
