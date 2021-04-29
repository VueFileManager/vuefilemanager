<?php
namespace App\Notifications\Oasis;

use Laravel\Cashier\Cashier;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

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

        return (new MailMessage)
            ->subject(__t('mail_order_subject'))
            ->greeting(__t('mail_greeting'))
            ->line(__t('mail_order_line_1'))
            ->line(__t('mail_tariff', [
                'name' => $this->plan['product']['name'],
                'storage' => Cashier::formatAmount($this->plan['plan']['amount']),
                'price' => format_gigabytes($this->plan['product']['metadata']['capacity']),
            ]))
            ->action(__t('mail_activation_action'), $url)
            ->line(__t('mail_order_line_2'))
            ->line(__t('mail_order_line_3'))
            ->salutation(__t('mail_salutation'));
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
        ];
    }
}
