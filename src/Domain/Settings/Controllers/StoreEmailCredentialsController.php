<?php
namespace Domain\Settings\Controllers;

use Artisan;
use Illuminate\Http\JsonResponse;
use Domain\Settings\Actions\TestSESConnectionAction;
use Domain\Settings\Actions\TestSMTPConnectionAction;
use Domain\Settings\Actions\TestMailgunConnectionAction;
use Domain\Settings\Actions\TestPostmarkConnectionAction;
use Domain\Settings\Requests\StoreEmailCredentialsRequest;

class StoreEmailCredentialsController
{
    public function __construct(
        private TestPostmarkConnectionAction $testPostmarkConnection,
        private TestMailgunConnectionAction $testMailgunConnection,
        private TestSMTPConnectionAction $testSMTPConnection,
        private TestSESConnectionAction $testSESConnection,
    ) {
    }

    /**
     * Set new email credentials to .env file
     */
    public function __invoke(
        StoreEmailCredentialsRequest $request
    ): JsonResponse {
        $message = [
            'type'    => 'success',
            'message' => 'The email credentials was successfully set',
        ];

        // Abort in demo mode
        if (is_demo()) {
            return response()->json($message);
        }

        if (! app()->runningUnitTests()) {
            // Test email connection
            match ($request->input('mailDriver')) {
                'smtp' => ($this->testSMTPConnection)([
                    'host'       => $request->input('smtp.host'),
                    'port'       => $request->input('smtp.port'),
                    'username'   => $request->input('smtp.username'),
                    'password'   => $request->input('smtp.password'),
                    'encryption' => $request->input('smtp.encryption') ?? '',
                    'email'      => $request->input('smtp.email'),
                ]),
                'mailgun' => ($this->testMailgunConnection)([
                    'domain'   => $request->input('mailgun.domain'),
                    'secret'   => $request->input('mailgun.secret'),
                    'endpoint' => $request->input('mailgun.endpoint'),
                    'sender'   => $request->input('mailgun.sender'),
                ]),
                'postmark' => ($this->testPostmarkConnection)([
                    'token'  => $request->input('postmark.token'),
                    'sender' => $request->input('postmark.sender'),
                ]),
                'ses' => ($this->testSESConnection)([
                    'access_key'        => $request->input('ses.access_key'),
                    'secret_access_key' => $request->input('ses.secret_access_key'),
                    'default_region'    => $request->input('ses.default_region'),
                    'session_token'     => $request->input('ses.session_token'),
                    'identity'          => $request->input('ses.sender'),
                ]),
                default => null
            };

            $mail = [
                'log'      => [
                    'MAIL_DRIVER' => 'log',
                ],
                'postmark' => [
                    'MAIL_DRIVER'       => 'postmark',
                    'POSTMARK_TOKEN'    => $request->input('postmark.token'),
                    'MAIL_FROM_ADDRESS' => $request->input('postmark.sender'),
                    'MAIL_FROM_NAME'    => $request->input('postmark.sender'),
                ],
                'smtp'     => [
                    'MAIL_DRIVER'       => 'smtp',
                    'MAIL_HOST'         => $request->input('smtp.host'),
                    'MAIL_PORT'         => $request->input('smtp.port'),
                    'MAIL_USERNAME'     => $request->input('smtp.username'),
                    'MAIL_PASSWORD'     => $request->input('smtp.password'),
                    'MAIL_ENCRYPTION'   => $request->input('smtp.encryption') ?? '',
                    'MAIL_FROM_ADDRESS' => $request->input('smtp.email') ?? $request->input('smtp.username'),
                    'MAIL_FROM_NAME'    => $request->input('smtp.email') ?? $request->input('smtp.username'),
                ],
                'ses'      => [
                    'MAIL_DRIVER'           => 'ses',
                    'AWS_ACCESS_KEY_ID'     => $request->input('ses.access_key'),
                    'AWS_SECRET_ACCESS_KEY' => $request->input('ses.secret_access_key'),
                    'AWS_DEFAULT_REGION'    => $request->input('ses.default_region'),
                    'AWS_SESSION_TOKEN'     => $request->input('ses.session_token'),
                    'MAIL_FROM_ADDRESS'     => $request->input('ses.sender'),
                    'MAIL_FROM_NAME'        => $request->input('ses.sender'),
                ],
                'mailgun'  => [
                    'MAIL_DRIVER'       => 'mailgun',
                    'MAILGUN_DOMAIN'    => $request->input('mailgun.domain'),
                    'MAILGUN_SECRET'    => $request->input('mailgun.secret'),
                    'MAILGUN_ENDPOINT'  => $request->input('mailgun.endpoint'),
                    'MAIL_FROM_ADDRESS' => $request->input('mailgun.sender'),
                    'MAIL_FROM_NAME'    => $request->input('mailgun.sender'),
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

        return response()->json($message);
    }
}
