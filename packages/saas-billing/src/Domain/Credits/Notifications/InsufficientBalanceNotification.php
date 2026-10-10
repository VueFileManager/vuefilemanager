<?php
namespace VueFileManager\Subscription\Domain\Credits\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InsufficientBalanceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Uh-oh! Your credit withdrawal for your pre-paid subscription failed'))
            ->greeting(__('Hi there'))
            ->line(__("It looks like your subscription credit withdrawal for your account didn't go through. Please make sure you have sufficient funds on your account and we'll give it another try!"))
            ->action(__('Fund Your Account'), url('/user/settings/billing'));
    }
}
