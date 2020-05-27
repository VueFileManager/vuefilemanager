<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define admin settings gate
        Gate::define('admin-settings', function ($user) {
            return $user->role === 'admin';
        });

        Passport::routes();

        Passport::tokensCan([
            'master'  => 'Master',
            'editor'  => 'Editor',
            'visitor' => 'Visitor',
        ]);

        Passport::setDefaultScope([
            'master',
            'editor',
            'visitor',
        ]);
    }
}
