<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SharedSendViaEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($emails, $token)
    {
        $this->emails = $emails;
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
        $notifiable->email = $this->emails;
        $shared_link = url(env('APP_URL') . '/shared' . '/' . $this->token );
       

        return (new MailMessage)
            ->subject(__('vuefilemanager.reset_password_subject') . config('vuefilemanager.app_name'))
            ->greeting(__('vuefilemanager.shared_link_email_greeting'))
            ->line(__('vuefilemanager.shared_link_email_user', ['user' => $this->user->name, 'email' => $this->user->email]))
            ->action(__('vuefilemanager.shared_link_email_link'), $shared_link);
            
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