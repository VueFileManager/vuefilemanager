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

        $name = $this->plan['product']['name'];
        $price = Cashier::formatAmount($this->plan['plan']['amount']);
        $storage = format_gigabytes($this->plan['product']['metadata']['capacity']);

        return (new MailMessage)
            ->subject('🏝 Potvrzeni Objednavky - OasisDrive')
            ->greeting('Vážený zákazníku,')
            ->line('Právě jste si úspěšně vytvořil registraci bezpečnostní datové služby OasisDrive.')
            ->line("Vámi vybraný tarif: $name - $storage za $price")
            ->action('Pro aktivaci klikněte zde', $url)
            ->line('Odkaz je platný 24 hodin.')
            ->line('Po dokončení registrace v odkazu Vám bude služba automaticky aktivována a lze ji ihned využívat.')
            ->line('S pozdravem a přáním hezkého dne')
            ->salutation('Tým Oasis Drive');
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
