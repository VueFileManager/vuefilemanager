<?php
namespace Tests\Domain\DunningEmails;

use Carbon\Carbon;
use Tests\TestCase;
use Tests\Models\User;
use Illuminate\Support\Facades\Notification;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\DunningEmails\Models\Dunning;
use VueFileManager\Subscription\Domain\Plans\Models\PlanMeteredFeature;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;
use VueFileManager\Subscription\Domain\DunningEmails\Actions\SendRepeatedDunningEmailToUsersAction;
use VueFileManager\Subscription\Domain\DunningEmails\Actions\ScanSubscriptionsToSendDunningEmailAction;
use VueFileManager\Subscription\Domain\DunningEmails\Notifications\DunningEmailToCoverAccountUsageNotification;

class DunningNotificationsTest extends TestCase
{
    /**
     * @test
     */
    public function it_trigger_usage_bigger_than_balance_filter_and_send_first_notification()
    {
        $user = User::factory()
            ->create([
                'created_at' => now()->subDays(60),
            ]);

        $user->creditBalance(5.00, 'USD');

        $plan = Plan::factory()
            ->create([
                'type'     => 'metered',
                'currency' => 'USD',
            ]);

        PlanMeteredFeature::factory()
            ->hasTiers([
                'first_unit' => 1,
                'last_unit'  => null,
                'per_unit'   => 0.29,
                'flat_fee'   => 2.49,
            ])
            ->create([
                'plan_id'            => $plan->id,
                'key'                => 'bandwidth',
                'aggregate_strategy' => 'sum_of_usage',
            ]);

        $subscription = Subscription::factory()
            ->create([
                'type'       => 'pre-paid',
                'status'     => 'active',
                'plan_id'    => $plan->id,
                'user_id'    => $user->id,
                'renews_at'  => now(),
                'created_at' => now()->subDays(15),
                'ends_at'    => null,
            ]);

        foreach (range(1, 15) as $i) {
            // Travel by time
            $this->travel(-1)->days();

            // Record usages - 3.645 total
            $subscription->recordUsage('bandwidth', 1);
        }

        // Reset date
        Carbon::setTestNow('1. January 2022');

        // Run fraud mechanism
        resolve(ScanSubscriptionsToSendDunningEmailAction::class)();

        // Do email assertions
        Notification::assertSentTo($user, DunningEmailToCoverAccountUsageNotification::class);

        $this->assertDatabaseHas('dunnings', [
            'user_id'  => $user->id,
            'sequence' => 1,
            'type'     => 'usage_bigger_than_balance',
        ]);
    }

    /**
     * @test
     */
    public function it_trigger_limit_usage_in_new_accounts_and_send_first_notification()
    {
        $user = User::factory()
            ->create(['created_at' => now()->subDays(15)]);

        $plan = Plan::factory()
            ->create([
                'type'     => 'metered',
                'currency' => 'USD',
            ]);

        PlanMeteredFeature::factory()
            ->hasTiers([
                'first_unit' => 1,
                'last_unit'  => null,
                'per_unit'   => 0.29,
                'flat_fee'   => 2.49,
            ])
            ->create([
                'plan_id'            => $plan->id,
                'key'                => 'bandwidth',
                'aggregate_strategy' => 'sum_of_usage',
            ]);

        $subscription = Subscription::factory()
            ->create([
                'type'       => 'pre-paid',
                'status'     => 'active',
                'plan_id'    => $plan->id,
                'user_id'    => $user->id,
                'renews_at'  => now(),
                'created_at' => now()->subDays(15),
                'ends_at'    => null,
            ]);

        foreach (range(1, 15) as $i) {
            // Travel by time
            $this->travel(-1)->days();

            // Record usages - 3.645 total
            $subscription->recordUsage('bandwidth', 1);
        }

        // Reset date
        Carbon::setTestNow('1. January 2022');

        // Run fraud mechanism
        resolve(ScanSubscriptionsToSendDunningEmailAction::class)();

        // Do email assertions
        Notification::assertSentTo($user, DunningEmailToCoverAccountUsageNotification::class);

        $this->assertDatabaseHas('dunnings', [
            'user_id'  => $user->id,
            'sequence' => 1,
            'type'     => 'limit_usage_in_new_accounts',
        ]);
    }

    /**
     * @test
     */
    public function it_send_three_consecutive_dunning_emails()
    {
        $user = User::factory()
            ->hasDunning([
                'type'     => 'limit_usage_in_new_accounts',
            ])
            ->create();

        $this->assertDatabaseHas('dunnings', [
            'sequence' => 1,
        ]);

        $this->travel(2)->days();

        resolve(SendRepeatedDunningEmailToUsersAction::class)();

        $this->assertDatabaseHas('dunnings', [
            'sequence' => 2,
        ]);

        $this->travel(2)->days();

        resolve(SendRepeatedDunningEmailToUsersAction::class)();

        $this->assertDatabaseHas('dunnings', [
            'sequence' => 3,
        ]);

        Notification::assertSentToTimes($user, DunningEmailToCoverAccountUsageNotification::class, 3);
    }

    /**
     * @test
     */
    public function first_limit_usage_in_new_accounts_notification()
    {
        $dunning = Dunning::factory()
            ->createOneQuietly([
                'type'     => 'limit_usage_in_new_accounts',
                'sequence' => 1,
            ]);

        $notification = (new DunningEmailToCoverAccountUsageNotification($dunning))->toMail();

        $this->assertStringContainsString('Please make first payment for your account to fund your usage.', $notification->subject);
        $this->assertStringContainsString('We are happy you are using our service. To continue to using our service, please make first payment for your account balance to fund your usage.', $notification->render());
    }

    /**
     * @test
     */
    public function second_limit_usage_in_new_accounts_notification()
    {
        $dunning = Dunning::factory()
            ->createOneQuietly([
                'type'     => 'limit_usage_in_new_accounts',
                'sequence' => 2,
            ]);

        $notification = (new DunningEmailToCoverAccountUsageNotification($dunning))->toMail();

        $this->assertStringContainsString('📆 Reminder: Please make first payment for your account to fund your usage.', $notification->subject);
        $this->assertStringContainsString('We are happy you are using our service. To continue to using our service, please make first payment for your account balance to fund your usage.', $notification->render());
    }

    /**
     * @test
     */
    public function third_limit_usage_in_new_accounts_notification()
    {
        $dunning = Dunning::factory()
            ->createOneQuietly([
                'type'     => 'limit_usage_in_new_accounts',
                'sequence' => 3,
            ]);

        $notification = (new DunningEmailToCoverAccountUsageNotification($dunning))->toMail();

        $this->assertStringContainsString('‼️ Uh-oh! Your functionality was restricted. Please make payment to continue using your account.', $notification->subject);
        $this->assertStringContainsString('We are sorry for the inconvenience with using our service. To continue to using our service, please make first payment for your account balance to fund your usage and your functionality will be allowed as soon as possible.', $notification->render());
    }

    /**
     * @test
     */
    public function first_usage_bigger_than_balance_notification()
    {
        $dunning = Dunning::factory()
            ->createOneQuietly([
                'type'     => 'usage_bigger_than_balance',
                'sequence' => 1,
            ]);

        $notification = (new DunningEmailToCoverAccountUsageNotification($dunning))->toMail();

        $this->assertStringContainsString("⚠️ You don't have sufficient funds in your account, please increase your account balance", $notification->subject);
        $this->assertStringContainsString('We are happy you are using our service. To continue to using our service, please increase your funds for your account balance to cover your usage.', $notification->render());
    }

    /**
     * @test
     */
    public function second_usage_bigger_than_balance_notification()
    {
        $dunning = Dunning::factory()
            ->createOneQuietly([
                'type'     => 'usage_bigger_than_balance',
                'sequence' => 2,
            ]);

        $notification = (new DunningEmailToCoverAccountUsageNotification($dunning))->toMail();

        $this->assertStringContainsString("📆 Reminder: You don't have sufficient funds in your account, please increase your account balance", $notification->subject);
        $this->assertStringContainsString('We are happy you are using our service. To continue to using our service, please increase your funds for your account balance to cover your usage.', $notification->render());
    }

    /**
     * @test
     */
    public function third_usage_bigger_than_balance_notification()
    {
        $dunning = Dunning::factory()
            ->createOneQuietly([
                'type'     => 'usage_bigger_than_balance',
                'sequence' => 3,
            ]);

        $notification = (new DunningEmailToCoverAccountUsageNotification($dunning))->toMail();

        $this->assertStringContainsString('‼️ Uh-oh! Your functionality was restricted. Please increase your funds for your account balance to cover your usage.', $notification->subject);
        $this->assertStringContainsString('We are sorry for the inconvenience with using our service. To continue to using our service, please increase your funds for your account balance to cover your usage and your functionality will be allowed as soon as possible.', $notification->render());
    }
}
