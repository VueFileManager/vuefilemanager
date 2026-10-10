<?php
namespace VueFileManager\Subscription\Domain\DunningEmails\Actions;

use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;
use VueFileManager\Subscription\Domain\Usage\Actions\SumUsageForCurrentPeriodAction;

class ScanSubscriptionsToSendDunningEmailAction
{
    public function __invoke(): void
    {
        // Get filters
        $usageBiggerThanBalance = config('subscription.metered_billing.fraud_prevention_mechanism.usage_bigger_than_balance');
        $limitUsageInNewAccounts = config('subscription.metered_billing.fraud_prevention_mechanism.limit_usage_in_new_accounts');

        // Check new users which reach the usage limit for the new account
        if ($limitUsageInNewAccounts['active']) {
            Subscription::query()
                ->where('type', 'pre-paid')
                ->where('status', 'active')
                ->whereHas('user', function ($q) {
                    $q
                        ->whereDate('created_at', '>=', now()->subDays(30)->toDateTimeString())
                        ->doesntHave('creditCards')
                        ->whereDoesntHave('transactions', function ($q) {
                            $q
                                ->where('type', 'credit')
                                ->whereNot('driver', 'system');
                        });
                })
                ->cursor()
                ->each(function ($subscription) use ($limitUsageInNewAccounts) {
                    // Get total cost of usage
                    $usage = resolve(SumUsageForCurrentPeriodAction::class)($subscription);

                    // Check if actual usage is bigger than account balance
                    if ($usage->sum('amount') >= $limitUsageInNewAccounts['amount'] && $subscription->user->dunning()->doesntExist()) {
                        $subscription->user->dunning()->create([
                            'type' => 'limit_usage_in_new_accounts',
                        ]);
                    }
                });
        }

        // Check users which has usage bigger than their current balance in the account
        if ($usageBiggerThanBalance['active']) {
            Subscription::query()
                ->where('type', 'pre-paid')
                ->where('status', 'active')
                ->whereHas('user', function ($q) {
                    $q
                        ->whereDate('created_at', '<=', now()->subDays(30)->toDateTimeString())
                        ->doesntHave('dunning')
                        ->doesntHave('creditCards');
                })
                ->cursor()
                ->each(function ($subscription) {
                    // Get total cost of usage
                    $usage = resolve(SumUsageForCurrentPeriodAction::class)($subscription);

                    // Check if actual usage is bigger than account balance
                    if ($usage->sum('amount') >= $subscription->user->balance->amount) {
                        $subscription->user->dunning()->create([
                            'type' => 'usage_bigger_than_balance',
                        ]);
                    }
                });
        }
    }
}
