<?php
namespace Domain\Subscriptions\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ConfirmStripePaymentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public array $payload
    ) {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__t('confirm_payment'))
            ->greeting(__t('confirm_payment_greeting', ['amount' => $this->payload['amount']]))
            ->line(__t('confirm_payment_line'))
            ->action(__t('confirm_payment_action'), $this->payload['url']);
    }
}
