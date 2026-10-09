<?php
namespace Tests\Domain\Plans;

use Tests\TestCase;
use Tests\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Tests\Mocking\Stripe\GetPlanStripeMocksClass;
use Tests\Mocking\PayPal\CreatePlanPayPalMocksClass;
use Tests\Mocking\PayPal\DeletePlanPayPalMocksClass;
use Tests\Mocking\PayPal\UpdatePlanPayPalMocksClass;
use Tests\Mocking\Stripe\CreatePlanStripeMocksClass;
use Tests\Mocking\Stripe\DeletePlanStripeMocksClass;
use Tests\Mocking\Stripe\UpdatePlanStripeMocksClass;
use Tests\Mocking\PayStack\CreatePlanPaystackMocksClass;
use Tests\Mocking\PayStack\DeletePlanPaystackMocksClass;
use Tests\Mocking\PayStack\UpdatePlanPaystackMocksClass;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\Plans\Models\PlanDriver;
use VueFileManager\Subscription\Domain\Plans\Models\PlanFixedFeature;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;

class FixedPlansTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_all_visible_plans_for_users()
    {
        $user = User::factory()
            ->create();

        $plan = Plan::factory()
            ->hasFixedFeatures(1)
            ->hasDrivers(2)
            ->create();

        $this
            ->actingAs($user)
            ->getJson('/api/subscriptions/plans')
            ->assertJsonFragment([
                'id' => $plan->id,
            ]);
    }

    /**
     * @test
     */
    public function it_get_all_plans_for_admin()
    {
        $admin = User::factory()
            ->create(['role' => 'admin']);

        $plan = Plan::factory()
            ->hasFixedFeatures(1)
            ->hasDrivers(2)
            ->create([
                'visible' => false,
            ]);

        $this
            ->actingAs($admin)
            ->getJson('/api/subscriptions/admin/plans')
            ->assertJsonFragment([
                'id' => $plan->id,
            ]);
    }

    /**
     * @test
     */
    public function it_create_fixed_plan()
    {
        $user = User::factory()
            ->create([
                'role' => 'admin',
            ]);

        $plan = Plan::factory()
            ->make();

        // Mocking Https
        resolve(CreatePlanPaystackMocksClass::class)($plan);
        resolve(CreatePlanPayPalMocksClass::class)($plan);
        resolve(CreatePlanStripeMocksClass::class)($plan);

        $this
            ->actingAs($user)
            ->post('/api/subscriptions/admin/plans', [
                'type'        => $plan->type,
                'name'        => $plan->name,
                'description' => $plan->description,
                'interval'    => $plan->interval,
                'amount'      => $plan->amount,
                'currency'    => 'USD',
                'features'    => [
                    'max_storage_amount' => 100,
                    'max_team_members'   => 6,
                ],
            ])
            ->assertCreated()
            ->assertJsonFragment([
                'name' => $plan->name,
            ]);

        collect(getActiveDrivers())
            ->each(
                fn ($driver) => $this
                    ->assertDatabaseHas('plan_drivers', [
                        'driver' => $driver,
                    ])
            );

        $this
            ->assertDatabaseHas('plans', [
                'type'        => 'fixed',
                'name'        => $plan->name,
                'description' => $plan->description,
                'currency'    => 'USD',
                'status'      => 'active',
            ])
            ->assertDatabaseHas('plan_fixed_features', [
                'key'   => 'max_storage_amount',
                'value' => 100,
            ])
            ->assertDatabaseHas('plan_fixed_features', [
                'key'   => 'max_team_members',
                'value' => 6,
            ]);

        // TODO: this can't be fixed, must be flexible for new gateway development
        //Http::assertSentCount(4);
    }

    /**
     * @test
     */
    public function it_update_plan()
    {
        $user = User::factory()
            ->create([
                'role' => 'admin',
            ]);

        $plan = Plan::factory()
            ->create();

        // Create plan features
        collect(['max_storage_amount', 'max_team_members'])
            ->each(
                fn ($feature) => PlanFixedFeature::create([
                    'plan_id'        => $plan->id,
                    'key'            => $feature,
                    'value'          => 10,
                ])
            );

        // Create plan drivers
        collect(getActiveDrivers())
            ->each(
                fn ($driver) => PlanDriver::create([
                    'driver'         => $driver,
                    'plan_id'        => $plan->id,
                    'driver_plan_id' => Str::random(),
                ])
            );

        resolve(UpdatePlanPayPalMocksClass::class)($plan);
        resolve(UpdatePlanStripeMocksClass::class)($plan);
        resolve(UpdatePlanPaystackMocksClass::class)($plan);

        // Attributes to update
        $planAttributes = [
            'visible'     => false,
            'name'        => 'New name',
            'description' => 'New description',
        ];

        // Features to update
        $planFeatures = [
            'max_storage_amount' => 120,
            'max_team_members'   => '12',
        ];

        // Update plan attributes one by one
        collect($planAttributes)
            ->each(function ($value, $key) use ($user, $plan) {
                $this
                    ->actingAs($user)
                    ->put("/api/subscriptions/admin/plans/{$plan->id}", [
                        $key => $value,
                    ])
                    ->assertOk();
            });

        // Update plan features one by one
        collect($planFeatures)
            ->each(function ($value, $key) use ($user, $plan) {
                $this
                    ->actingAs($user)
                    ->patch("/api/subscriptions/admin/plans/$plan->id/features", [
                        $key => $value,
                    ])
                    ->assertOk();
            });

        // Check if synchronization record was created
        $this->assertTrue(cache()->has('action.synchronize-plans'));

        // Synchronize plans
        Artisan::call('subscription:synchronize-plans');

        // Check updated results
        $this
            ->assertDatabaseHas('plans', [
                'type'        => 'fixed',
                'visible'     => false,
                'name'        => 'New name',
                'description' => 'New description',
            ])
            ->assertDatabaseHas('plan_fixed_features', [
                'key'   => 'max_storage_amount',
                'value' => 120,
            ])
            ->assertDatabaseHas('plan_fixed_features', [
                'key'   => 'max_team_members',
                'value' => 12,
            ])
            ->assertTrue(! cache()->has('action.synchronize-plans'));
    }

    /**
     * @test
     */
    public function it_delete_plan_as_archived()
    {
        $user = User::factory()
            ->create([
                'role' => 'admin',
            ]);

        $plan = Plan::factory()
            ->hasFixedFeatures(1)
            ->create();

        Subscription::factory()
            ->create([
                'type'       => 'fixed',
                'status'     => 'active',
                'plan_id'    => $plan->id,
                'user_id'    => $user->id,
            ]);

        // Create plan drivers
        collect(getActiveDrivers())
            ->each(
                fn ($driver) => PlanDriver::create([
                    'driver'         => $driver,
                    'plan_id'        => $plan->id,
                    'driver_plan_id' => Str::random(),
                ])
            );

        resolve(GetPlanStripeMocksClass::class)($plan);
        resolve(DeletePlanPayPalMocksClass::class)($plan);
        resolve(DeletePlanStripeMocksClass::class)($plan);
        resolve(DeletePlanPaystackMocksClass::class)($plan);

        // 2. delete plans
        $this
            ->actingAs($user)
            ->delete("/api/subscriptions/admin/plans/{$plan->id}")
            ->assertOk();

        $this->assertDatabaseHas('plans', [
            'type'        => 'fixed',
            'id'          => $plan->id,
            'status'      => 'archived',
        ]);
    }

    /**
     * @test
     */
    public function it_delete_plan_hardly()
    {
        $user = User::factory()
            ->create([
                'role' => 'admin',
            ]);

        $plan = Plan::factory()
            ->hasFixedFeatures(1)
            ->create();

        // Create plan drivers
        collect(getActiveDrivers())
            ->each(
                fn ($driver) => PlanDriver::create([
                    'driver'         => $driver,
                    'plan_id'        => $plan->id,
                    'driver_plan_id' => Str::random(),
                ])
            );

        resolve(GetPlanStripeMocksClass::class)($plan);
        resolve(DeletePlanPayPalMocksClass::class)($plan);
        resolve(DeletePlanStripeMocksClass::class)($plan);
        resolve(DeletePlanPaystackMocksClass::class)($plan);

        // 2. delete plans
        $this
            ->actingAs($user)
            ->delete("/api/subscriptions/admin/plans/{$plan->id}")
            ->assertOk();

        $this
            ->assertDatabaseCount('plan_fixed_features', 0)
            ->assertDatabaseCount('plan_drivers', 0)
            ->assertDatabaseMissing('plans', [
                'type' => 'fixed',
                'id'   => $plan->id,
            ]);
    }
}
