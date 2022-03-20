<?php
namespace Domain\Subscriptions\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ChargeFromCreditCardFailedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__t('charge_from_card_failed_subject'))
            ->greeting(__t('hello'))
            ->line(__t('charge_from_card_failed_line'))
            ->action(__t('charge_from_card_failed_action'), url('/user/settings/billing'));
    }
}
