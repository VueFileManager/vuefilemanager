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
            $drivers = [
                'local' => [
                    'FILESYSTEM_DRIVER' => 'local',
                ],
                's3' => [
                    'FILESYSTEM_DRIVER'     => 's3',
                    'S3_ACCESS_KEY_ID'      => $request->input('storage.key') ?? null,
                    'S3_SECRET_ACCESS_KEY'  => $request->input('storage.secret') ?? null,
                    'S3_DEFAULT_REGION'     => $request->input('storage.region') ?? null,
                    'S3_BUCKET'             => $request->input('storage.bucket') ?? null,
                    'S3_URL'                => $request->input('storage.endpoint') ?? null,
                ],
            ];

            // Get storage driver from request
            $driver = 'local' === $request->input('storage.driver') ? 'local' : 's3';

            // Storage credentials for storage
            setEnvironmentValue(
                $drivers[$driver]
            );

            // Store credentials for mail
            // TODO: add options for mailgun
            setEnvironmentValue([
                'MAIL_DRIVER'     => $request->input('mail.driver'),
                'MAIL_HOST'       => $request->input('mail.host'),
                'MAIL_PORT'       => $request->input('mail.port'),
                'MAIL_USERNAME'   => $request->input('mail.username'),
                'MAIL_PASSWORD'   => $request->input('mail.password'),
                'MAIL_ENCRYPTION' => $request->input('mail.encryption'),
            ]);

            Artisan::call('config:cache');
        }

        return response('Done', 204);
    }
}
