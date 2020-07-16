<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Laravel\Cashier\Payment;

class ConfirmPayment extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The PaymentIntent identifier.
     *
     * @var string
     */
    public $paymentId;

    /**
     * The payment amount.
     *
     * @var string
     */
    public $amount;

    /**
     * Create a new payment confirmation notification.
     *
     * @param  \Laravel\Cashier\Payment  $payment
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->paymentId = $payment->id;
        $this->amount = $payment->amount();
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
        $url = route('cashier.payment', ['id' => $this->paymentId]);

        return (new MailMessage)
            ->subject(__('cashier.confirm_payment'))
            ->greeting(__('cashier.confirm_amount', ['amount' => $this->amount]))
            ->line(__('cashier.confirm_description'))
            ->action(__('cashier.confirm_button'), $url);
    }
}
