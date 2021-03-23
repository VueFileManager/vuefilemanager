<?php

namespace App\Notifications\Oasis;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Laravel\Cashier\Cashier;

class PaymentRequiredNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param $order
     * @param $plan
     */
    public function __construct($order, $plan)
    {
        $this->order = $order;
        $this->plan = $plan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url("/platba/{$this->order['id']}");

        $price = Cashier::formatAmount($this->plan['plan']['amount']);
        $storage = format_gigabytes($this->plan['product']['metadata']['capacity']);

        return (new MailMessage)
            ->subject('🏝 Potvrzeni Objednavky - OasisDrive')
            ->greeting('Dobry den')
            ->line('🏝 dekujeme za Vasi objednavku. Potvrzenim objednavky se Vas ucet automaticky zaktivuje a vytvori se Vam digitalni prostor pro Vase dulezite dokumenty.')
            ->line("Datovy tarif: Standard: $storage - $price")
            ->action('Prejst na platbu', $url)
            ->line('Dekujeme, Vas tym OasisDrive');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
