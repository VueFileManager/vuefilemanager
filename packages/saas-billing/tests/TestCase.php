<?php
namespace Tests;

use Carbon\Carbon;
use Tests\Models\User;
use Illuminate\Foundation\Application;
use Laravel\Sanctum\SanctumServiceProvider;
use Illuminate\Support\Facades\Notification;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Kyslik\ColumnSortable\ColumnSortableServiceProvider;
use VueFileManager\Subscription\SubscriptionServiceProvider;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;

class TestCase extends Orchestra
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        Carbon::setTestNow('1. January 2022');

        $this->withoutExceptionHandling();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'VueFileManager\\Subscription\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ColumnSortableServiceProvider::class,
            SubscriptionServiceProvider::class,
            SanctumServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $this->loadDefaultEnv($app);

        config()->set('auth.providers.users.model', User::class);
        config()->set('subscription.metered_billing.settlement_period', 30);

        // Fake (non-real) gateway credentials, all gateway API calls are mocked with Http::fake()
        config()->set('subscription.credentials', [
            'stripe'   => [
                'secret'      => 'fake-stripe-secret',
                'public_key'  => 'fake-stripe-public-key',
                'webhook_key' => 'fake-stripe-webhook-secret',
            ],
            'paystack' => [
                'secret'     => 'fake-paystack-secret',
                'public_key' => 'fake-paystack-public-key',
            ],
            'paypal'   => [
                'id'         => 'fake-paypal-client-id',
                'secret'     => 'fake-paypal-client-secret',
                'webhook_id' => 'fake-paypal-webhook-id',
                'is_live'    => false,
            ],
        ]);
    }

    protected function loadDefaultEnv(Application $app): void
    {
        $app->useEnvironmentPath(__DIR__ . '/..');
        $app->bootstrapWith([LoadEnvironmentVariables::class]);

        parent::getEnvironmentSetUp($app);
    }
}
