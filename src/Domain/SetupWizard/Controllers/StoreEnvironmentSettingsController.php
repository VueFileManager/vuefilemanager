<?php

namespace Domain\SetupWizard\Controllers;

use Artisan;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Domain\SetupWizard\Requests\StoreEnvironmentSetupRequest;

class StoreEnvironmentSettingsController extends Controller
{
    /**
     * Store environment setup
     */
    public function __invoke(
        StoreEnvironmentSetupRequest $request,
    ): Response
    {
        if (!app()->runningUnitTests()) {
            $drivers = [
                'local' => [
                    'FILESYSTEM_DRIVER' => 'local',
                ],
                's3'    => [
                    'FILESYSTEM_DRIVER'    => 's3',
                    'S3_ACCESS_KEY_ID'     => $request->input('storage.key') ?? null,
                    'S3_SECRET_ACCESS_KEY' => $request->input('storage.secret') ?? null,
                    'S3_DEFAULT_REGION'    => $request->input('storage.region') ?? null,
                    'S3_BUCKET'            => $request->input('storage.bucket') ?? null,
                    'S3_URL'               => $request->input('storage.endpoint') ?? null,
                ],
            ];

            // Get storage driver from request
            $driver = 'local' === $request->input('storage.driver') ? 'local' : 's3';

            // Storage credentials for storage
            setEnvironmentValue(
                $drivers[$driver]
            );

            $mail = [
                'log'     => [
                    'MAIL_DRIVER' => 'log',
                ],
                'postmark'     => [
                    'POSTMARK_TOKEN' => $request->input('postmark.token'),
                ],
                'smtp'    => [
                    'MAIL_DRIVER'     => 'smtp',
                    'MAIL_HOST'       => $request->input('mail.host'),
                    'MAIL_PORT'       => $request->input('mail.port'),
                    'MAIL_USERNAME'   => $request->input('mail.username'),
                    'MAIL_PASSWORD'   => $request->input('mail.password'),
                    'MAIL_ENCRYPTION' => $request->input('mail.encryption'),
                ],
                'ses'     => [
                    'MAIL_DRIVER'           => 'ses',
                    'AWS_ACCESS_KEY_ID'     => $request->input('ses.access_key'),
                    'AWS_SECRET_ACCESS_KEY' => $request->input('ses.secret_access_key'),
                    'AWS_DEFAULT_REGION'    => $request->input('ses.default_region'),
                    'AWS_SESSION_TOKEN'     => $request->input('ses.session_token'),
                ],
                'mailgun' => [
                    'MAIL_DRIVER'      => 'mailgun',
                    'MAILGUN_DOMAIN'   => $request->input('mailgun.domain'),
                    'MAILGUN_SECRET'   => $request->input('mailgun.secret'),
                    'MAILGUN_ENDPOINT' => $request->input('mailgun.endpoint'),

                ],
            ];

            // Store credentials for mail
            setEnvironmentValue([
                $mail[$request->input('mail.driver')]
            ]);

            Artisan::call('config:cache');
        }

        return response('Done', 204);
    }
}
