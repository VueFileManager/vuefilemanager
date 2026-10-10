<?php
namespace VueFileManager\Subscription\Domain\Credits\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BonusCreditAddedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        public string $bonus
    ) {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(__("You Received {$this->bonus}"))
            ->greeting(__('Hi there'))
            ->line(__("You received credit bonus $this->bonus from us. Happy spending!"));
    }
}
