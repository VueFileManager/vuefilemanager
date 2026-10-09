<?php
namespace VueFileManager\Subscription;

use Illuminate\Routing\Router;
use Spatie\LaravelPackageTools\Package;
use Illuminate\Support\Facades\Validator;
use Illuminate\Console\Scheduling\Schedule;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use VueFileManager\Subscription\Support\EngineManager;
use VueFileManager\Subscription\App\Console\Commands\SynchronizePlansCommand;
use VueFileManager\Subscription\App\Scheduler\HaltExpiredSubscriptionsSchedule;
use VueFileManager\Subscription\App\Scheduler\CheckAndTriggerBillingAlertsSchedule;
use VueFileManager\Subscription\App\Scheduler\SettlePrePaidSubscriptionPeriodSchedule;
use VueFileManager\Subscription\Domain\FailedPayments\Actions\RetryChargeFromPaymentCardAction;
use VueFileManager\Subscription\Domain\DunningEmails\Actions\SendRepeatedDunningEmailToUsersAction;
use VueFileManager\Subscription\Domain\DunningEmails\Actions\ScanSubscriptionsToSendDunningEmailAction;

class SubscriptionServiceProvider extends PackageServiceProvider
{
    public function registeringPackage()
    {
        $this->app->singleton(EngineManager::class, fn ($app) => new EngineManager($app));
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('subscription')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommands([
                SynchronizePlansCommand::class,
            ])
            ->hasRoutes([
                'api',
            ]);
    }

    public function bootingPackage()
    {
        $router = $this->app->make(Router::class);

        // Register middleware
        $router->aliasMiddleware('admin', config('subscription.middlewares.admin'));

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Register validator
        Validator::extend('string_or_integer', fn ($attribute, $value) => is_string($value) || is_integer($value));

        // Schedule background operations
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);

            // Synchronize plans with services if there were some changes
            $schedule->command('subscription:synchronize-plans')
                ->everyFiveMinutes();

            // Halt expired subscriptions
            $schedule->call(HaltExpiredSubscriptionsSchedule::class)
                ->daily();

            // Settle pre-paid subscriptions
            $schedule->call(SettlePrePaidSubscriptionPeriodSchedule::class)
                ->daily();

            // Check billing alerts
            $schedule->call(CheckAndTriggerBillingAlertsSchedule::class)
                ->daily();

            // Try failed credit card charge again
            $schedule->call(RetryChargeFromPaymentCardAction::class)
                ->daily();

            // Check subscriptions to detect whom to send dunning email
            $schedule->call(ScanSubscriptionsToSendDunningEmailAction::class)
                ->daily();

            // Get dunning records and send second and third reminders
            $schedule->call(SendRepeatedDunningEmailToUsersAction::class)
                ->daily();
        });
    }
}
