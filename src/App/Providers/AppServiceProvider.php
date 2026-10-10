<?php
namespace App\Providers;

use PDOException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Support\Listeners\SubscriptionEventSubscriber;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Register event subscribers
        Event::subscribe(SubscriptionEventSubscriber::class);

        // Configure the rate limiters for the application
        $this->configureRateLimiting();

        // TODO: temporary
        config()->set('session.lifetime', 15120);

        // Set subscription config
        $this->setSubscriptionConfig();

        // Set app locale
        $this->setLocale();

        // Get all migrations with all directories
        $this->setMigrations();
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(1000)->by($request->user()->id)
                : Limit::perMinute(100)->by($request->ip());
        });

        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));

        RateLimiter::for('two-factor', fn (Request $request) => Limit::perMinute(5)->by($request->session()->get('login.id')));
    }

    private function setMigrations(): void
    {
        $mainPath = database_path('migrations');
        $directories = glob($mainPath . '/*', GLOB_ONLYDIR);

        $this->loadMigrationsFrom(
            array_merge([$mainPath], $directories)
        );
    }

    private function setSubscriptionConfig(): void
    {
        if (app()->runningUnitTests() || app()->runningInConsole()) {
            return;
        }

        // Try to set settings for fraud prevention mechanism
        try {
            $settings = getAllSettings();

            config([
                'subscription.metered_billing.fraud_prevention_mechanism' => [
                    'usage_bigger_than_balance'   => [
                        'active' => isset($settings->usage_bigger_than_balance) ? intval($settings->usage_bigger_than_balance) : true,
                    ],
                    'limit_usage_in_new_accounts' => [
                        'active' => isset($settings->limit_usage_in_new_accounts) ? intval($settings->limit_usage_in_new_accounts) : true,
                        'amount' => isset($settings->limit_usage_in_new_accounts_amount) ? intval($settings->limit_usage_in_new_accounts_amount) : 20,
                    ],
                ]
            ]);
        } catch (PDOException $e) {}
    }

    private function setLocale(): void
    {
        try {
            $appLocale = get_settings('language') ?? 'en';
        } catch (\PDOException $e) {
            $appLocale = 'en';
        }

        // Set locale for application
        app()->setLocale($appLocale);

        // Set locale for carbon dates
        setlocale(LC_TIME, $appLocale . '_' . mb_strtoupper($appLocale));
    }
}
