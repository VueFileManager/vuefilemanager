<?php
namespace App\Providers;

use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Illuminate\Support\ServiceProvider;
use App\Users\Actions\ResetUserPassword;
use Illuminate\Cache\RateLimiting\Limit;
use App\Users\Actions\UpdateUserPassword;
use Illuminate\Support\Facades\RateLimiter;
use App\Users\Actions\UpdateUserProfileInformation;

class FortifyServiceProvider extends ServiceProvider
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
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(20)->by($request->email.$request->ip()));

        RateLimiter::for('two-factor', fn (Request $request) => Limit::perMinute(5)->by($request->session()->get('login.id')));
    }
}
