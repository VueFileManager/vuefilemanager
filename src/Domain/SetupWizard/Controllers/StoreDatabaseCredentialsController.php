<?php
namespace Domain\SetupWizard\Controllers;

use Artisan;
use Illuminate\Http\Response;
use Domain\Settings\Models\Setting;
use App\Http\Controllers\Controller;
use Doctrine\DBAL\Driver\PDOException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Domain\SetupWizard\Requests\StoreDatabaseCredentialsRequest;

class StoreDatabaseCredentialsController extends Controller
{
    /**
     * Set up database credentials
     */
    public function __invoke(
        StoreDatabaseCredentialsRequest $request
    ): Response {
        if (! app()->runningUnitTests()) {
            try {
                // Set temporary database connection
                config([
                    'database' => [
                        'connections' => [
                            'tests' => [
                                'driver'   => $request->input('connection'),
                                'host'     => $request->input('host'),
                                'port'     => $request->input('port'),
                                'database' => $request->input('name'),
                                'username' => $request->input('username'),
                                'password' => $request->input('password'),
                            ],
                        ],
                    ],
                ]);

                // Test connection
                \DB::connection('test')->getPdo();
            } catch (PDOException $e) {
                throw new HttpException(500, $e->getMessage());
            }

            // TODO: add SANCTUM_STATEFUL_DOMAINS parameter

            setEnvironmentValue([
                'DB_CONNECTION' => $request->input('connection'),
                'DB_HOST'       => $request->input('host'),
                'DB_PORT'       => $request->input('port'),
                'DB_DATABASE'   => $request->input('name'),
                'DB_USERNAME'   => $request->input('username'),
                'DB_PASSWORD'   => $request->input('password'),
            ]);

            Artisan::call('config:cache');

            Artisan::call('key:generate', [
                '--force' => true,
            ]);

            Artisan::call('migrate:fresh', [
                '--force' => true,
            ]);
        }

        // Store setup wizard progress
        Setting::forceCreate([
            'name'  => 'setup_wizard_database',
            'value' => 1,
        ]);

        return response('Done', 204);
    }
}
