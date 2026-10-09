<?php
namespace VueFileManager\Subscription\Domain\FailedPayments\Notifications;

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
            ->subject(__('Uh-oh! Your withdrawal from your credit card for pre-paid subscription failed'))
            ->greeting(__('Hi there'))
            ->line(__("It looks like withdrawal from your credit card for pre-paid subscription for your account didn't go through. Please check your credit card or register new credit card for your account and we'll give it another try!"))
            ->action(__('Go to Billing Settings'), url('/user/settings/billing'));
    }
}
