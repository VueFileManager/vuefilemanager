<?php
namespace Domain\Maintenance\Actions;

use Gate;
use Artisan;

class UpgradeDatabaseAction
{
    public function __invoke(): bool
    {
        // Check admin permission
        Gate::authorize('maintenance');

        return Artisan::call('migrate', [
            '--force' => true,
        ]);
    }
}
