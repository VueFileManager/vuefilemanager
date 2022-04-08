<?php
namespace Domain\Settings\Actions;

use Mail;
use Domain\Settings\Mail\TestMail;
use Symfony\Component\Mailer\Exception\LogicException;
use Symfony\Component\Mailer\Exception\TransportException;

class TestPostmarkConnectionAction
{
    public function __invoke(array $credentials)
    {
        try {
            // Set temporary mail connection
            config([
                'mail.driver'             => 'postmark',
                'mail.from.address'       => $credentials['sender'],
                'mail.from.name'          => $credentials['sender'],
                'services.postmark.token' => $credentials['token'],
            ]);

            // Send test email
            Mail::to($credentials['sender'])->send(new TestMail($credentials['sender']));
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
