<?php

namespace Tests\Feature\Oasis;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class OasisSubscriptionTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_get_subscription_request_details()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        $user
            ->subscriptionRequest()
            ->create([
                'requested_plan' => 'virtualni-sanon-basic',
            ]);

        $this->getJson("/api/oasis/subscription-request/{$user->subscriptionRequest->id}")
            ->assertStatus(200)
            ->assertJsonFragment([
                'id'             => $user->subscriptionRequest->id,
                'requested_plan' => 'virtualni-sanon-basic',
            ]);
    }
}
