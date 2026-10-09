<?php
namespace Tests\App\Console;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Tests\Mocking\PayPal\UpdatePlanPayPalMocksClass;
use Tests\Mocking\Stripe\CreatePlanStripeMocksClass;
use Tests\Mocking\Stripe\UpdatePlanStripeMocksClass;
use Tests\Mocking\PayStack\UpdatePlanPaystackMocksClass;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\Plans\Models\PlanDriver;

class SynchronizePlanCommandTest extends TestCase
{
    /**
     * @test
     */
    public function it_create_plan_after_added_new_driver()
    {
        $plan = Plan::factory()
            ->create();

        PlanDriver::factory()
            ->create([
                'plan_id' => $plan->id,
                'driver'  => 'paypal',
            ]);

        PlanDriver::factory()
            ->create([
                'plan_id' => $plan->id,
                'driver'  => 'paystack',
            ]);

        // Call plan synchronization
        cache()->add('action.synchronize-plans', now()->toString());

        resolve(CreatePlanStripeMocksClass::class)($plan);
        resolve(UpdatePlanPayPalMocksClass::class)($plan);
        resolve(UpdatePlanStripeMocksClass::class)($plan);
        resolve(UpdatePlanPaystackMocksClass::class)($plan);

        Artisan::call('subscription:synchronize-plans');

        $this->assertDatabaseHas('plan_drivers', [
            'driver' => 'stripe',
        ]);
    }
}
