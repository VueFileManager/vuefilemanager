<?php
namespace Domain\Settings\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public string $emailFrom
    ) {
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this->from($this->emailFrom, 'Test')
            ->subject('Test Mail')
            ->view('mails.test-mail');
    }
}
