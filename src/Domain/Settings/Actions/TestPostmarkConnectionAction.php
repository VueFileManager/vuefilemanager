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
            // Send test email through temporary mail connection
            Mail::build([
                'transport' => 'postmark',
                'token'     => $credentials['token'],
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
