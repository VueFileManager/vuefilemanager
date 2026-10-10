<?php
namespace VueFileManager\Subscription\Domain\Usage\Actions;

use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;

class SumUsageForCurrentPeriodAction
{
    public function __invoke(Subscription $subscription)
    {
        return $subscription
            ->plan
            ->meteredFeatures
            ->map(function ($feature) use ($subscription) {
                // Get first tier
                $tier = $feature->tiers()->first();

                $usageQuery = $subscription
                    ->usages()
                    ->where('created_at', '>=', $subscription->renews_at->subdays(30))
                    ->where('subscription_id', $subscription->id)
                    ->where('metered_feature_id', $feature->id);

                $usage = match ($feature->aggregate_strategy) {
                    'sum_of_usage'  => $usageQuery->sum('quantity'),
                    'maximum_usage' => $usageQuery->max('quantity'),
                };

                return [
                    'feature' => $feature->key,
                    'amount'  => round(($tier->per_unit * $usage) + $tier->flat_fee, 4),
                    'usage'   => $usage,
                ];
            });
    }
}
