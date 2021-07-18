<?php
namespace App\Providers;

use Illuminate\Support\Facades\App;
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
        try {
            $app_locale = get_setting('language') ?? 'en';
        } catch (\PDOException $exception) {
            $app_locale = 'en';
        }

        // Set locale for application
        app()->setLocale($app_locale);

        // Set locale for carbon dates
        setlocale(LC_TIME, $app_locale . '_' . mb_strtoupper($app_locale));

        // Get all migrations with all directories
        $this->loadMigrationsFrom(
            $this->get_migration_paths()
        );
    }

    /**
     * @return array
     */
    private function get_migration_paths(): array
    {
        $mainPath = database_path('migrations');
        $directories = glob($mainPath . '/*', GLOB_ONLYDIR);

        return array_merge([$mainPath], $directories);
    }
}
