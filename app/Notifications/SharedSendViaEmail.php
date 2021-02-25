<?php

namespace App\Notifications;

use App\Setting;
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
        $app_name = strval(Setting::where('name', 'app_title')->pluck('value')[0]);
        
        return (new MailMessage)
            ->subject(__('vuefilemanager.shared_link_email_subject' , ['user' => $this->user->name]))
            ->greeting(__('vuefilemanager.shared_link_email_greeting'))
            ->line(__('vuefilemanager.shared_link_email_user', ['user' => $this->user->name, 'email' => $this->user->email]))
            ->action(__('vuefilemanager.shared_link_email_link'), url('/shared', ['token' => $this->token]))
            ->salutation(__('vuefilemanager.shared_link_email_salutation', ['app_name' => $app_name ? $app_name : 'VueFileManager']));
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