<?php
namespace App\Users\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegistrationBonusAddedNotification extends Notification implements ShouldQueue
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

    /**
     * Get the notification's delivery channels.
     */
    public function via(mixed $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'category'    => 'gift',
            'title'       => __t('you_received_bonus', ['bonus' => $this->bonus]),
            'description' => __t('you_received_registration_bonus_note', ['bonus' => $this->bonus]),
        ];
    }
}
