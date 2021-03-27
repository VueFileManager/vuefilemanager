<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SharedSendViaEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
        $this->user = Auth::user();
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
        return (new MailMessage)
            ->subject(__t('shared_link_email_subject' , ['user' => $this->user->name]))
            ->greeting(__t('shared_link_email_greeting'))
            ->line(__t('shared_link_email_user', ['user' => $this->user->name, 'email' => $this->user->email]))
            ->action(__t('shared_link_email_link'), url('/shared', ['token' => $this->token]))
            ->salutation(__t('shared_link_email_salutation', ['app_name' => get_setting('app_title') ?? 'VueFileManager']));
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