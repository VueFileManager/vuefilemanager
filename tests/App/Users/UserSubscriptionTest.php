<?php

namespace Tests\App\Users;

use App\Users\Actions\FormatUsageEstimatesAction;
use Tests\TestCase;
use App\Users\Models\User;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\Plans\Models\PlanFeature;
use VueFileManager\Subscription\Domain\Plans\Models\PlanMeteredFeature;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;
use VueFileManager\Subscription\Support\Events\SubscriptionWasCreated;
use VueFileManager\Subscription\Support\Events\SubscriptionWasExpired;
use VueFileManager\Subscription\Support\Events\SubscriptionWasUpdated;

class UserSubscriptionTest extends TestCase
{
    /**
     * @test
     */
    public function it_set_user_limitations_for_new_subscription()
    {
        $plan = Plan::factory()
            ->has(PlanFeature::factory()
                ->count(2)
                ->sequence(
                    [
                        'key'   => 'max_storage_amount',
                        'value' => 200,
                    ],
                    [
                        'key'   => 'max_team_members',
                        'value' => 20,
                    ],
                ), 'features')
            ->create();

        $user = User::factory()
            ->hasSubscription([
                'plan_id' => $plan->id,
            ])
            ->create();

        SubscriptionWasCreated::dispatch($user->subscription);

        $this->assertDatabaseHas('user_limitations', [
            'max_storage_amount' => 200,
            'max_team_members'   => 20,
        ]);
    }

    /**
     * @test
     */
    public function it_set_user_limitations_for_updated_subscription()
    {
        $plan = Plan::factory()
            ->has(PlanFeature::factory()
                ->count(2)
                ->sequence(
                    [
                        'key'   => 'max_storage_amount',
                        'value' => 200,
                    ],
                    [
                        'key'   => 'max_team_members',
                        'value' => 20,
                    ],
                ), 'features')
            ->create();

        $user = User::factory()
            ->hasSubscription([
                'plan_id' => $plan->id,
            ])
            ->create();

        SubscriptionWasUpdated::dispatch($user->subscription);

        $this->assertDatabaseHas('user_limitations', [
            'max_storage_amount' => 200,
            'max_team_members'   => 20,
        ]);
    }

    /**
     * @test
     */
    public function it_set_user_limitations_for_expired_subscription()
    {
        $user = User::factory()
            ->hasSubscription()
            ->create();

        $user->limitations()->update([
            'max_storage_amount' => 200,
            'max_team_members'   => 20,
        ]);

        SubscriptionWasExpired::dispatch($user->subscription);

        $this->assertDatabaseHas('user_limitations', [
            'max_storage_amount' => 5,
            'max_team_members'   => 5,
        ]);
    }

    /**
     * @test
     */
    public function it_format_price_estimates()
    {
        $usages = collect([
            [
                'feature' => 'bandwidth',
                "amount"  => 7546.96,
                "usage"   => 26024,
            ], [
                "feature" => "storage",
                "amount"  => 476.28,
                "usage"   => 3969,
            ]
        ]);

        // Format usage estimates
        $estimates = resolve(FormatUsageEstimatesAction::class)('USD', $usages)
            ->toArray();

        $expected = [
            [
                "feature" => "bandwidth",
                "amount"  => 7.54696,
                "cost"    => "$7.55",
                "usage"   => "26.02GB",
            ], [
                "feature" => "storage",
                "amount"  => 0.47628,
                "cost"    => "$0.48",
                "usage"   => "3.97GB",
            ]
        ];

        $this->assertEquals($expected, $estimates);
    }
}
