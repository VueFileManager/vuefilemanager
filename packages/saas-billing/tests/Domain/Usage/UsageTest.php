<?php
namespace Tests\Domain\Usage;

use Tests\TestCase;
use Tests\Models\User;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\Plans\Models\PlanMeteredFeature;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;
use VueFileManager\Subscription\Domain\Usage\Actions\SumUsageForCurrentPeriodAction;

class UsageTest extends TestCase
{
    /**
     * @test
     */
    public function it_store_usage()
    {
        $plan = Plan::factory()
            ->hasMeteredFeatures([
                'key' => 'bandwidth',
            ])
            ->create([
                'type' => 'metered',
            ]);

        $subscription = Subscription::factory()
            ->hasDriver([
                'driver' => 'paypal',
            ])
            ->create([
                'plan_id' => $plan->id,
            ]);

        $subscription->recordUsage('bandwidth', 0.12345);

        $this->assertDatabaseHas('usages', [
            'metered_feature_id' => $plan->meteredFeatures()->first()->id,
            'subscription_id'    => $subscription->id,
            'quantity'           => 0.12345,
        ]);
    }

    /**
     * @test
     */
    public function it_get_estimates_for_current_period()
    {
        $user = User::factory()
            ->create();

        $plan = Plan::factory()
            ->create([
                'type'     => 'metered',
                'currency' => 'USD',
            ]);

        PlanMeteredFeature::factory()
            ->hasTiers([
                'first_unit' => 1,
                'last_unit'  => null,
                'per_unit'   => 0.19,
                'flat_fee'   => 0,
            ])
            ->create([
                'plan_id'            => $plan->id,
                'key'                => 'bandwidth',
                'aggregate_strategy' => 'sum_of_usage',
            ]);

        PlanMeteredFeature::factory()
            ->hasTiers([
                'first_unit' => 1,
                'last_unit'  => null,
                'per_unit'   => 1.29,
                'flat_fee'   => 0,
            ])
            ->create([
                'plan_id'            => $plan->id,
                'key'                => 'storage',
                'aggregate_strategy' => 'maximum_usage',
            ]);

        $subscription = Subscription::factory()
            ->create([
                'type'       => 'pre-paid',
                'status'     => 'active',
                'plan_id'    => $plan->id,
                'user_id'    => $user->id,
                'renews_at'  => now()->addDays(16),
                'created_at' => now()->subDays(14),
                'updated_at' => now()->subDays(14),
                'ends_at'    => null,
            ]);

        foreach (range(1, 6) as $i) {
            // Travel by time
            $this->travel(-1)->days();

            // Record usages
            $subscription->recordUsage('bandwidth', 1 + $i);
            $subscription->recordUsage('storage', 1 + $i);
        }

        $estimates = resolve(SumUsageForCurrentPeriodAction::class)($subscription);

        $this->assertEquals(9.03, $estimates->where('feature', 'storage')->first()['amount']);
        $this->assertEquals(5.13, $estimates->where('feature', 'bandwidth')->first()['amount']);

        $this->assertEquals(14.16, $estimates->sum('amount'));
    }
}
