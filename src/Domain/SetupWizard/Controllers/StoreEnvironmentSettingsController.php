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
                    'FILESYSTEM_DRIVER'     => $request->input('storage.driver') ?? null,
                    'AWS_ACCESS_KEY_ID'     => $request->input('storage.key') ?? null,
                    'AWS_SECRET_ACCESS_KEY' => $request->input('storage.secret') ?? null,
                    'AWS_DEFAULT_REGION'    => $request->input('storage.region') ?? null,
                    'AWS_BUCKET'            => $request->input('storage.bucket') ?? null,
                ],
                'spaces' => [
                    'FILESYSTEM_DRIVER'  => $request->input('storage.driver') ?? null,
                    'DO_SPACES_KEY'      => $request->input('storage.key') ?? null,
                    'DO_SPACES_SECRET'   => $request->input('storage.secret') ?? null,
                    'DO_SPACES_ENDPOINT' => $request->input('storage.endpoint') ?? null,
                    'DO_SPACES_REGION'   => $request->input('storage.region') ?? null,
                    'DO_SPACES_BUCKET'   => $request->input('storage.bucket') ?? null,
                ],
                'wasabi' => [
                    'FILESYSTEM_DRIVER' => $request->input('storage.driver') ?? null,
                    'WASABI_KEY'        => $request->input('storage.key') ?? null,
                    'WASABI_SECRET'     => $request->input('storage.secret') ?? null,
                    'WASABI_ENDPOINT'   => $request->input('storage.endpoint') ?? null,
                    'WASABI_REGION'     => $request->input('storage.region') ?? null,
                    'WASABI_BUCKET'     => $request->input('storage.bucket') ?? null,
                ],
                'backblaze' => [
                    'FILESYSTEM_DRIVER'  => $request->input('storage.driver') ?? null,
                    'BACKBLAZE_KEY'      => $request->input('storage.key') ?? null,
                    'BACKBLAZE_SECRET'   => $request->input('storage.secret') ?? null,
                    'BACKBLAZE_ENDPOINT' => $request->input('storage.endpoint') ?? null,
                    'BACKBLAZE_REGION'   => $request->input('storage.region') ?? null,
                    'BACKBLAZE_BUCKET'   => $request->input('storage.bucket') ?? null,
                ],
                'oss' => [
                    'FILESYSTEM_DRIVER'     => $request->input('storage.driver') ?? null,
                    'OSS_ACCESS_KEY_ID'     => $request->input('storage.key') ?? null,
                    'OSS_SECRET_ACCESS_KEY' => $request->input('storage.secret') ?? null,
                    'OSS_ENDPOINT'          => $request->input('storage.endpoint') ?? null,
                    'OSS_REGION'            => $request->input('storage.region') ?? null,
                    'OSS_BUCKET'            => $request->input('storage.bucket') ?? null,
                ],
            ];

            // Storage credentials for storage
            setEnvironmentValue(
                $drivers[$request->input('storage.driver')]
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
