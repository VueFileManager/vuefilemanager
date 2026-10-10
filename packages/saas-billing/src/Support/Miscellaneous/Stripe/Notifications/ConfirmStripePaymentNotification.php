<?php
namespace VueFileManager\Subscription\Support\Miscellaneous\Stripe\Notifications;

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
            ->subject(__('Confirm Payment'))
            ->greeting(__('Confirm your :amount payment', ['amount' => $this->payload['amount']]))
            ->line(__('Extra confirmation is needed to process your payment. Please continue to the payment page by clicking on the button below.'))
            ->action(__('Confirm Payment'), $this->payload['url']);
    }
}
