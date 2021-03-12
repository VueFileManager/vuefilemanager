<?php

namespace App\Http\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendContactMessage extends Mailable
{
    use Queueable, SerializesModels;
    private $request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from')['address'])
            ->replyTo($this->request['email'])
            ->subject('New Contact Message from ' . $this->request['email'])
            ->view('mails.contact-message')
            ->with('request', $this->request);
    }
}
