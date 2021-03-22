<?php

namespace App\Notifications\Oasis;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentRequiredNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param $order
     */
    public function __construct($order)
    {
        $this->order = $order;
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
        $url = url("/platba/{$this->order['id']}");

        return (new MailMessage)
                    ->subject('🏝 Platobne instrukcie pre zakupenie balicka a aktivaciu Vasho konta')
                    ->line('🏝 Platobne instrukcie pre zakupenie balicka a aktivaciu Vasho konta')
                    ->action('Prejst na platbu', $url)
                    ->line('Dakujeme za zaujem o nase sluzby!');
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
