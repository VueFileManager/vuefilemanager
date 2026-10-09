<?php
namespace Domain\Settings\Actions;

use Mail;
use Exception;
use Domain\Settings\Mail\TestMail;
use Symfony\Component\Mailer\Exception\LogicException;
use Symfony\Component\Mailer\Exception\TransportException;

class TestSESConnectionAction
{
    public function __invoke(array $credentials)
    {
        try {
            // Send test email through temporary mail connection
            Mail::build([
                'transport' => 'ses',
                'key'       => $credentials['access_key'],
                'secret'    => $credentials['secret_access_key'],
                'region'    => $credentials['default_region'],
                'token'     => $credentials['session_token'] ?? null,
            ])->to($credentials['identity'])->send(new TestMail($credentials['identity']));
        } catch (TransportException | LogicException | Exception $error) {
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
