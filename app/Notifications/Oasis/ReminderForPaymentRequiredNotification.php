<?php

namespace App\Notifications\Oasis;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Laravel\Cashier\Cashier;

class ReminderForPaymentRequiredNotification extends Notification
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
            ->line(__t('mail_reminder_line_1'))
            ->line(__t('mail_tariff', [
                'name'    => $this->plan['product']['name'],
                'storage' => Cashier::formatAmount($this->plan['plan']['amount']),
                'price'   => format_gigabytes($this->plan['product']['metadata']['capacity']),
            ]))
            ->line(__t('mail_reminder_line_2'))
            ->action(__t('mail_activation_action'), $url)
            ->line(__t('mail_reminder_line_3'))
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
            //
        ];
    }
}
