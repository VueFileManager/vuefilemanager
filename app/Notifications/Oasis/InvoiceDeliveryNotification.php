<?php
namespace App\Notifications\Oasis;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class InvoiceDeliveryNotification extends Notification
{
    use Queueable;

    private $invoice;
    private $user;

    /**
     * Create a new notification instance.
     *
     * @param $user
     * @param $invoice
     */
    public function __construct($user, $invoice)
    {
        $this->user = $user;
        $this->invoice = $invoice;
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
        return (new MailMessage)
            ->subject('New invoice')
            ->greeting(__t('mail_greeting'))
            ->line($this->user->settings->name . ' sent you an invoice.')
            ->salutation(__t('mail_salutation'))
            ->attach(storage_path('app/' . invoice_path($this->invoice)), [
                'as' => 'name.pdf',
                'mime' => 'application/pdf',
            ]);
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
