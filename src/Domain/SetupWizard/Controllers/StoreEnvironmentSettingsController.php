<?php
namespace Domain\SetupWizard\Controllers;

use Artisan;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Settings\DTO\S3CredentialsData;
use Domain\Settings\Actions\TestS3ConnectionAction;
use Domain\Settings\Actions\TestFTPConnectionAction;
use Domain\Settings\Actions\TestSESConnectionAction;
use Domain\Settings\Actions\TestSMTPConnectionAction;
use Domain\Settings\Actions\TestMailgunConnectionAction;
use Domain\Settings\Actions\TestPostmarkConnectionAction;
use Domain\SetupWizard\Requests\StoreEnvironmentSetupRequest;

class StoreEnvironmentSettingsController extends Controller
{
    public function __construct(
        private TestS3ConnectionAction $testS3Connection,
        private TestSESConnectionAction $testSESConnection,
        private TestFTPConnectionAction $testFTPConnection,
        private TestSMTPConnectionAction $testSMTPConnection,
        private TestMailgunConnectionAction $testMailgunConnection,
        private TestPostmarkConnectionAction $testPostmarkConnection,
    ) {
    }

    /**
     * Store environment setup
     */
    public function __invoke(
        StoreEnvironmentSetupRequest $request,
    ): JsonResponse {
        if (! app()->runningUnitTests()) {
            // Get storage driver from request
            $StorageDriver = match ($request->input('storage.driver')) {
                's3', 'storj', 'spaces', 'wasabi', 'backblaze', 'oss', 'other' => 's3',
                'local' => 'local',
                'ftp'   => 'ftp',
            };

            // Test driver connection
            match ($StorageDriver) {
                's3' => ($this->testS3Connection)(
                    S3CredentialsData::fromRequest($request)
                ),
                'ftp' => ($this->testFTPConnection)([
                    'host'     => $request->input('storage.ftp.host'),
                    'user'     => $request->input('storage.ftp.user'),
                    'password' => $request->input('storage.ftp.password'),
                ]),
                default => null
            };

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

            $setup = [
                'broadcasting' => [
                    'pusher' => [
                        'BROADCAST_DRIVER'   => 'pusher',
                        'PUSHER_APP_ID'      => $request->input('broadcast.id'),
                        'PUSHER_APP_KEY'     => $request->input('broadcast.key'),
                        'PUSHER_APP_SECRET'  => $request->input('broadcast.secret'),
                        'PUSHER_APP_CLUSTER' => $request->input('broadcast.cluster'),
                        'PUSHER_APP_HOST'    => '',
                        'PUSHER_APP_PORT'    => '',
                        'PUSHER_APP_TLS'     => 'true',
                    ],
                    'native' => [
                        'BROADCAST_DRIVER'   => 'pusher',
                        'PUSHER_APP_ID'      => 'local',
                        'PUSHER_APP_KEY'     => 'local',
                        'PUSHER_APP_SECRET'  => 'local',
                        'PUSHER_APP_CLUSTER' => 'local',
                        'PUSHER_APP_HOST'    => $request->input('broadcast.host'),
                        'PUSHER_APP_PORT'    => '',
                        'PUSHER_APP_TLS'     => $request->boolean('tls') ? 'true' : 'false',
                    ],
                    'none'   => [
                        'BROADCAST_DRIVER' => 'null',
                    ],
                ],
                'drivers'      => [
                    'local' => [
                        'FILESYSTEM_DISK' => 'local',
                    ],
                    's3'    => [
                        'FILESYSTEM_DISK'      => 's3',
                        'S3_ACCESS_KEY_ID'     => $request->input('storage.s3.key') ?? null,
                        'S3_SECRET_ACCESS_KEY' => $request->input('storage.s3.secret') ?? null,
                        'S3_DEFAULT_REGION'    => $request->input('storage.s3.region') ?? null,
                        'S3_BUCKET'            => $request->input('storage.s3.bucket') ?? null,
                        'S3_URL'               => $request->input('storage.s3.endpoint') ?? null,
                    ],
                    'ftp' => [
                        'FILESYSTEM_DISK' => 'ftp',
                        'FTP_HOST'        => $request->input('storage.ftp.host') ?? null,
                        'FTP_USERNAME'    => $request->input('storage.ftp.user') ?? null,
                        'FTP_PASSWORD'    => $request->input('storage.ftp.password') ?? null,
                    ],
                ],
                'mail'         => [
                    'log'      => [
                        'MAIL_DRIVER'       => 'log',
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
                ],
                'environment'  => [
                    'production' => [
                        'APP_ENV'   => 'production',
                        'APP_DEBUG' => 'false',
                    ],
                    'local'      => [
                        'APP_ENV'          => 'local',
                        'APP_DEBUG'        => 'true',
                        'QUEUE_CONNECTION' => 'sync',
                    ],
                ],
                'others'       => [
                    'APP_URL'                  => url('/'),
                    'SANCTUM_STATEFUL_DOMAINS' => request()->getHost() . ',' . request()->getHost() . ':' . request()->getPort(),
                ],
            ];

            // Set other environment variables
            setEnvironmentValue(array_merge(
                $setup['broadcasting'][$request->input('broadcast.driver')],
                $setup['environment'][$request->input('environment')],
                $setup['mail'][$request->input('mailDriver')],
                $setup['drivers'][$StorageDriver],
                $setup['others'],
            ));

            Artisan::call('config:cache');
        }

        return response()->json([
            'type'    => 'success',
            'message' => 'The environment was set successfully',
        ]);
    }
}
