<?php
namespace App\Users\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $token
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        $reset_url = url('/create-new-password?token=' . $this->token);
        $app_name = get_settings('app_title') ?? 'VueFileManager';

        return (new MailMessage)
            ->subject(__t('reset_password_subject') .  $app_name)
            ->greeting(__t('hello'))
            ->line(__t('reset_password_line_1'))
            ->action(__t('reset_password'), $reset_url)
            ->line(__t('reset_password_line_2'))
            ->salutation(__t('salutation') . ', ' .  $app_name);
    }
}
