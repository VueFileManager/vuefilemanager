<?php
namespace App\Providers;

use PDOException;
use Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // TODO: temporary
        config()->set('session.lifetime', 15120);

        // Set subscription config
        $this->setSubscriptionConfig();

        // Set app locale
        $this->setLocale();

        // Get all migrations with all directories
        $this->setMigrations();
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
