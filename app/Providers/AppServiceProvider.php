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
        $get_time_locale = App::getLocale() . '_' . mb_strtoupper(App::getLocale());

        // Set locale for carbon dates
        setlocale(LC_TIME, $get_time_locale);

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
