<?php
namespace Tests\Domain\Plans;

use Tests\TestCase;
use Tests\Models\User;

class MeteredPlanTest extends TestCase
{
    /**
     * @test
     */
    public function it_create_metered_plan()
    {
        $user = User::factory()
            ->create(['role' => 'admin']);

        $this
            ->actingAs($user)
            ->post('/api/subscriptions/admin/plans', [
                'type'        => 'metered',
                'name'        => 'Basic Plan',
                'description' => 'Pay as you go is the best fit',
                'currency'    => 'USD',
                'meters'      => [
                    [
                        'key'                => 'bandwidth',
                        'aggregate_strategy' => 'sum_of_usage',
                        'tiers'              => [
                            [
                                'first_unit' => 1,
                                'last_unit'  => null,
                                'per_unit'   => 0.019,
                                'flat_fee'   => 2.49,
                            ],
                        ],
                    ],
                    [
                        'key'                => 'storage',
                        'aggregate_strategy' => 'maximum_usage',
                        'tiers'              => [
                            [
                                'first_unit' => 1,
                                'last_unit'  => null,
                                'per_unit'   => 0.09,
                                'flat_fee'   => null,
                            ],
                        ],
                    ],
                ],
            ])
            ->assertCreated()
            ->assertJsonFragment([
                'name' => 'Basic Plan',
                'type' => 'metered',
            ]);

        $this
            ->assertDatabaseHas('plans', [
                'type'        => 'metered',
                'name'        => 'Basic Plan',
                'description' => 'Pay as you go is the best fit',
                'currency'    => 'USD',
                'status'      => 'active',
            ])
            ->assertDatabaseHas('plan_metered_features', [
                'key'                => 'bandwidth',
                'aggregate_strategy' => 'sum_of_usage',
            ])
            ->assertDatabaseHas('plan_metered_features', [
                'key'                => 'storage',
                'aggregate_strategy' => 'maximum_usage',
            ])
            ->assertDatabaseHas('metered_tiers', [
                'first_unit' => 1,
                'last_unit'  => null,
                'per_unit'   => 0.019,
                'flat_fee'   => 2.49,
            ])
            ->assertDatabaseHas('metered_tiers', [
                'first_unit' => 1,
                'last_unit'  => null,
                'per_unit'   => 0.09,
                'flat_fee'   => null,
            ]);
    }
}
