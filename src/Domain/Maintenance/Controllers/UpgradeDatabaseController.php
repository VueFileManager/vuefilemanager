<?php


namespace Domain\Maintenance\Controllers;


use App\Http\Controllers\Controller;
use Artisan;
use Gate;

class UpgradeDatabaseController extends Controller
{
    public function __invoke(): mixed
    {
        // Check admin permission
        Gate::authorize('maintenance');

        $command = Artisan::call('migrate', [
            '--force' => true,
        ]);

        if ($command === 0) {
            echo 'Operation was successful.';
        }

        if ($command === 1) {
            echo 'Operation failed.';
        }

        return $command;
    }
}