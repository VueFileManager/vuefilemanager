<?php
namespace App\Users\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    use Queueable;

    private $token;

    /**
     * Create a new notification instance.
     *
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
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

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
        ];
    }
}
