<?php
namespace Domain\Settings\Actions;

use Mail;
use Domain\Settings\Mail\TestMail;
use Symfony\Component\Mailer\Exception\LogicException;
use Symfony\Component\Mailer\Exception\TransportException;

class TestMailgunConnectionAction
{
    /**
     * Throw an Exception if connection isn't successful.
     *
     * @return never
     */
    public function __invoke(array $credentials)
    {
        try {
            // Send test email through temporary mail connection
            Mail::build([
                'transport' => 'mailgun',
                'domain'    => $credentials['domain'],
                'secret'    => $credentials['secret'],
                'endpoint'  => $credentials['endpoint'] ?: 'api.mailgun.net',
            ])->to($credentials['sender'])->send(new TestMail($credentials['sender']));
        } catch (TransportException | LogicException $error) {
            abort(
                response()->json([
                    'type'    => 'mailer-connection-error',
                    'title'   => 'Mail Connection Error',
                    'message' => $error->getMessage(),
                ], 401)
            );
        }
    }
}
