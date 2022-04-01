<?php
namespace Domain\Settings\Controllers;

use Mail;
use Artisan;
use Illuminate\Http\Response;
use Domain\Settings\Mail\TestMail;
use Symfony\Component\Mailer\Exception\LogicException;
use Domain\Settings\Requests\StoreEmailCredentialsRequest;
use Symfony\Component\Mailer\Exception\TransportException;

class StoreEmailCredentialsController
{
    /**
     * Set new email credentials to .env file
     */
    public function __invoke(StoreEmailCredentialsRequest $request): Response
    {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        if (! app()->runningUnitTests()) {
            // Test smtp server
            if ($request->input('mailDriver') === 'smtp') {
                try {
                    // Get credentials
                    $credentials = [
                        'smtp' => [
                            'driver'       => 'smtp',
                            'host'         => $request->input('smtp.host'),
                            'port'         => $request->input('smtp.port'),
                            'username'     => $request->input('smtp.username'),
                            'password'     => $request->input('smtp.password'),
                            'encryption'   => $request->input('smtp.encryption') ?? '',
                            'from.address' => $request->input('smtp.email') ?? $request->input('smtp.username'),
                            'from.name'    => $request->input('smtp.email') ?? $request->input('smtp.username'),
                        ],
                    ];

                    // Set temporary mail connection
                    config(['mail' => $credentials['smtp']]);

                    $sender = $request->input('smtp.email') ?? $request->input('smtp.username');

                    // Send test email
                    Mail::to('test@hi5ve.digital')->send(new TestMail($sender));
                } catch (TransportException|LogicException $error) {
                    return response([
                        'type'    => 'mailer-connection-error',
                        'title'   => 'Mail Connection Error',
                        'message' => $error->getMessage(),
                    ], 401);
                }
            }

            $mail = [
                'log'      => [
                    'MAIL_DRIVER' => 'log',
                ],
                'postmark' => [
                    'MAIL_DRIVER'    => 'postmark',
                    'POSTMARK_TOKEN' => $request->input('postmark.token'),
                ],
                'smtp' => [
                    'MAIL_DRIVER'       => 'smtp',
                    'MAIL_HOST'         => $request->input('smtp.host'),
                    'MAIL_PORT'         => $request->input('smtp.port'),
                    'MAIL_USERNAME'     => $request->input('smtp.username'),
                    'MAIL_PASSWORD'     => $request->input('smtp.password'),
                    'MAIL_ENCRYPTION'   => $request->input('smtp.encryption') ?? '',
                    'MAIL_FROM_ADDRESS' => $request->input('smtp.email') ?? '"${MAIL_USERNAME}"',
                    'MAIL_FROM_NAME'    => $request->input('smtp.email') ?? '"${MAIL_USERNAME}"',
                ],
                'ses'      => [
                    'MAIL_DRIVER'           => 'ses',
                    'AWS_ACCESS_KEY_ID'     => $request->input('ses.access_key'),
                    'AWS_SECRET_ACCESS_KEY' => $request->input('ses.secret_access_key'),
                    'AWS_DEFAULT_REGION'    => $request->input('ses.default_region'),
                    'AWS_SESSION_TOKEN'     => $request->input('ses.session_token'),
                ],
                'mailgun'  => [
                    'MAIL_DRIVER'      => 'mailgun',
                    'MAILGUN_DOMAIN'   => $request->input('mailgun.domain'),
                    'MAILGUN_SECRET'   => $request->input('mailgun.secret'),
                    'MAILGUN_ENDPOINT' => $request->input('mailgun.endpoint'),
                ],
            ];

            // Store credentials for mail
            setEnvironmentValue(
                $mail[$request->input('mailDriver')]
            );

            // Clear config cache
            Artisan::call('config:clear');
            Artisan::call('config:cache');
        }

        return response('Done', 204);
    }
}
