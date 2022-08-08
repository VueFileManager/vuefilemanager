<?php
namespace Domain\SetupWizard\Controllers;

use DB;
use Artisan;
use Illuminate\Http\JsonResponse;
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
    ): JsonResponse {
        if (! app()->runningUnitTests()) {
            try {
                // Set temporary database connection
                config([
                    'database.connections.mysql' => [
                        'driver'   => $request->input('connection'),
                        'host'     => $request->input('host'),
                        'port'     => $request->input('port'),
                        'database' => $request->input('name'),
                        'username' => $request->input('username'),
                        'password' => $request->input('password') ?? '',
                    ],
                ]);

                // Refresh connection
                DB::reconnect('mysql');

                // Test connection
                DB::connection('mysql')->getPdo();
            } catch (PDOException $e) {
                throw new HttpException(500, $e->getMessage());
            }

            Artisan::call('migrate:fresh', [
                '--force' => true,
            ]);

            // Set database variables
            setEnvironmentValue([
                'DB_CONNECTION' => $request->input('connection'),
                'DB_HOST'       => $request->input('host'),
                'DB_PORT'       => $request->input('port'),
                'DB_DATABASE'   => $request->input('name'),
                'DB_USERNAME'   => $request->input('username'),
                'DB_PASSWORD'   => $request->input('password') ?? '',
            ]);

            Artisan::call('config:cache');
        }

        return response()->json([
            'type'    => 'success',
            'message' => 'The database was set successfully',
        ]);
    }
}
