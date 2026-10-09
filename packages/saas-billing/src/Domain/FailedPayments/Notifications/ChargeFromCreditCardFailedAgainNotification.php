<?php
namespace VueFileManager\Subscription\Domain\FailedPayments\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ChargeFromCreditCardFailedAgainNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Uh-oh! Your withdrawal from your credit card for pre-paid subscription failed once again'))
            ->greeting(__('Hi there'))
            ->line(__("It looks like withdrawal from your credit card for pre-paid subscription for your account didn't go through once again. Please check your credit card or register new credit card for your account and we'll give it another try!"))
            ->action(__('Update Your Payment Information'), url('/user/settings/billing'));
    }
}
