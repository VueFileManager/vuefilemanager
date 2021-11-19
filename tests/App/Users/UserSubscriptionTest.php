<?php
namespace Tests\App\Users;

use Tests\TestCase;
use App\Users\Models\User;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\Plans\Models\PlanFeature;
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
            'max_storage_amount' => 1,
            'max_team_members'   => 5,
        ]);
    }
}
