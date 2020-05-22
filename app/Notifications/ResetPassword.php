<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
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

        return (new MailMessage)
            ->subject(__('vuefilemanager.reset_password_subject') . config('vuefilemanager.app_name'))
            ->greeting(__('vuefilemanager.reset_password_greeting'))
            ->line(__('vuefilemanager.reset_password_line_1'))
            ->action(__('vuefilemanager.reset_password_action'), $reset_url)
            ->line(__('vuefilemanager.reset_password_line_2'))
            ->salutation(__('vuefilemanager.salutation') . ', ' . config('vuefilemanager.app_name'));
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
            //
        ];
    }
}
