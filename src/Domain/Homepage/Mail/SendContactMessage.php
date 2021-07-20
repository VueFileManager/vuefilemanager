<?php
namespace Domain\Homepage\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private array $request
    ) {
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this->from(config('mail.from')['address'])
            ->replyTo($this->request['email'])
            ->subject('New Contact Message from ' . $this->request['email'])
            ->view('mails.contact-message')
            ->with('request', $this->request);
    }
}
