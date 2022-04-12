<?php
namespace Domain\Settings\Actions;

use Mail;
use Domain\Settings\Mail\TestMail;
use Symfony\Component\Mailer\Exception\LogicException;
use Symfony\Component\Mailer\Exception\TransportException;

class TestSMTPConnectionAction
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
            config(['mail' => [
                'driver'       => 'smtp',
                'host'         => $credentials['host'],
                'port'         => $credentials['port'],
                'username'     => $credentials['username'],
                'password'     => $credentials['password'],
                'encryption'   => $credentials['encryption'] ?? '',
                'from'         => [
                    'address' => $credentials['email'] ?? $credentials['username'],
                    'name'    => $credentials['email'] ?? $credentials['username'],
                ],
            ]]);

            // Get sender
            $sender = $credentials['email'] ?? $credentials['username'];

            // Send test email
            Mail::to($sender)->send(new TestMail($sender));
        } catch (TransportException | LogicException $error) {
            abort(
                response()->json([
                    'type'    => 'mailer-connection-error',
                    'title'   => 'Mailer Connection Error - Wrong Credentials',
                    'message' => $error->getMessage(),
                ], 401)
            );
        }
    }
}
