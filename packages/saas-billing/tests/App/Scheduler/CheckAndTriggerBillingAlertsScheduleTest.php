<?php
namespace Tests\App\Scheduler;

use Tests\TestCase;
use Tests\Models\User;
use Illuminate\Support\Facades\Notification;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\Plans\Models\PlanMeteredFeature;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;
use VueFileManager\Subscription\App\Scheduler\CheckAndTriggerBillingAlertsSchedule;
use VueFileManager\Subscription\Domain\BillingAlerts\Notifications\BillingAlertTriggeredNotification;

class CheckAndTriggerBillingAlertsScheduleTest extends TestCase
{
    /**
     * @test
     */
    public function it_trigger_billing_alert_and_send_notification()
    {
        $user = User::factory()
            ->create();

        $user->billingAlert()
            ->create([
                'amount' => 5,
            ]);

        $plan = Plan::factory()
            ->create([
                'type'     => 'metered',
                'currency' => 'USD',
            ]);

        PlanMeteredFeature::factory()
            ->hasTiers([
                'first_unit' => 1,
                'last_unit'  => null,
                'per_unit'   => 1.49,
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
                'per_unit'   => 2.4,
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

        foreach (range(1, 2) as $i) {
            // Travel by time
            $this->travel(-1)->days();

            // Record usages - 3.645 total
            $subscription->recordUsage('bandwidth', 1);
            $subscription->recordUsage('storage', 1);
        }

        resolve(CheckAndTriggerBillingAlertsSchedule::class)();

        $this->assertDatabaseHas('billing_alerts', [
            'triggered' => true,
        ]);

        Notification::assertSentTo($user, BillingAlertTriggeredNotification::class);
    }
}
