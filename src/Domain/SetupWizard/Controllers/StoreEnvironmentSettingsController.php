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
    ): Response {
        if (! app()->runningUnitTests()) {
            $setup = [
                'broadcasting' => [
                    'pusher' => [
                        'BROADCAST_DRIVER'   => 'pusher',
                        'PUSHER_APP_ID'      => $request->input('broadcast.id'),
                        'PUSHER_APP_KEY'     => $request->input('broadcast.key'),
                        'PUSHER_APP_SECRET'  => $request->input('broadcast.secret'),
                        'PUSHER_APP_CLUSTER' => $request->input('broadcast.cluster'),
                    ],
                    'native' => [
                        'BROADCAST_DRIVER'   => 'pusher',
                        'PUSHER_APP_ID'      => 'local',
                        'PUSHER_APP_KEY'     => 'local',
                        'PUSHER_APP_SECRET'  => 'local',
                        'PUSHER_APP_CLUSTER' => 'local',
                        'PUSHER_APP_HOST'    => $request->input('broadcast.host'),
                        'PUSHER_APP_PORT'    => $request->input('broadcast.port'),
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
                        'S3_ACCESS_KEY_ID'     => $request->input('storage.key') ?? null,
                        'S3_SECRET_ACCESS_KEY' => $request->input('storage.secret') ?? null,
                        'S3_DEFAULT_REGION'    => $request->input('storage.region') ?? null,
                        'S3_BUCKET'            => $request->input('storage.bucket') ?? null,
                        'S3_URL'               => $request->input('storage.endpoint') ?? null,
                    ],
                ],
                'mail'         => [
                    'log'      => [
                        'MAIL_DRIVER' => 'log',
                    ],
                    'postmark' => [
                        'MAIL_DRIVER'    => 'postmark',
                        'POSTMARK_TOKEN' => $request->input('postmark.token'),
                    ],
                    'smtp'     => [
                        'MAIL_DRIVER'     => 'smtp',
                        'MAIL_HOST'       => $request->input('smtp.host'),
                        'MAIL_PORT'       => $request->input('smtp.port'),
                        'MAIL_USERNAME'   => $request->input('smtp.username'),
                        'MAIL_PASSWORD'   => $request->input('smtp.password'),
                        'MAIL_ENCRYPTION' => $request->input('smtp.encryption'),
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

            // Get storage driver from request
            $driver = 'local' === $request->input('storage.driver') ? 'local' : 's3';

            // Set other environment variables
            setEnvironmentValue(array_merge(
                $setup['broadcasting'][$request->input('broadcast.driver')],
                $setup['environment'][$request->input('environment')],
                $setup['mail'][$request->input('mailDriver')],
                $setup['drivers'][$driver],
                $setup['others'],
            ));

            Artisan::call('config:cache');
        }

        return response('Done', 204);
    }
}
