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
            // Set temporary mail connection
            config([
                'mail.driver'         => 'ses',
                'mail.from.address'   => $credentials['identity'],
                'mail.from.name'      => $credentials['identity'],
                'services.ses.key'    => $credentials['access_key'],
                'services.ses.secret' => $credentials['secret_access_key'],
                'services.ses.region' => $credentials['default_region'],
                'services.ses.token'  => $credentials['session_token'] ?? null,
            ]);

            // Send test email
            Mail::to($credentials['identity'])->send(new TestMail($credentials['identity']));
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
