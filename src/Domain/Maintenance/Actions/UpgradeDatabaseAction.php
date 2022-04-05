<?php
namespace Domain\Maintenance\Actions;

use Artisan;

class UpgradeDatabaseAction
{
    public function __invoke(): bool
    {
        return Artisan::call('migrate', [
            '--force' => true,
        ]);
    }
}
