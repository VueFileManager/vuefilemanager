<?php
namespace VueFileManager\Subscription\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use VueFileManager\Subscription\Domain\Plans\Actions\SynchronizePlansAction;

class SynchronizePlansCommand extends Command
{
    public $signature = 'subscription:synchronize-plans';

    public $description = 'Synchronize plan data';

    public function handle()
    {
        $this->info('Starting synchronizing your plans...');

        $canSynchronizePlans = cache()->has('action.synchronize-plans');

        // Synchronize plans
        if ($canSynchronizePlans) {
            $this->synchronizePlans();
        }

        // Don't synchronize plans
        if (! $canSynchronizePlans) {
            $this->info('Nothing to synchronize.');
        }
    }

    public function synchronizePlans()
    {
        resolve(SynchronizePlansAction::class)();

        // Remove synchronize action
        cache()->delete('action.synchronize-plans');

        // Log last synchronization time
        Log::info('Last plan synchronization at: ' . now()->toString());

        // Show message in console
        $this->info('All your plans was synchronized, congratulations! 🥳🥳🥳');
    }
}
