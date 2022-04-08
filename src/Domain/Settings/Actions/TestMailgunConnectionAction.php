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
            // Set temporary mail connection
            config([
                'mail'     => [
                    'driver' => 'mailgun',
                ],
                'services' => [
                    'mailgun' => [
                        'domain'   => $credentials['domain'],
                        'secret'   => $credentials['secret'],
                        'endpoint' => $credentials['endpoint'],
                    ],
                ],
            ]);

            // Send test email
            Mail::to('test@hi5ve.digital')->send(new TestMail('example@domain.com'));
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
