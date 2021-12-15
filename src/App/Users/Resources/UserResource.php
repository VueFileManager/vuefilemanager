<?php

namespace App\Users\Resources;

use ByteUnits\Metric;
use Domain\Folders\Resources\FolderCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use VueFileManager\Subscription\Domain\Credits\Resources\BalanceResource;
use VueFileManager\Subscription\Domain\Subscriptions\Resources\SubscriptionResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $subscriptionType = get_settings('subscription_type');

        $isMeteredSubscription = $subscriptionType === 'metered';
        $isFixedSubscription = $subscriptionType === 'fixed';

        return [
            'data' => [
                'id'            => $this->id,
                'type'          => 'user',
                'attributes'    => [
                    'avatar'                    => $this->settings->avatar,
                    'email'                     => is_demo() ? obfuscate_email($this->email) : $this->email,
                    'role'                      => $this->role,
                    'two_factor_authentication' => $this->two_factor_secret ? true : false,
                    'folders'                   => $this->folder_tree,
                    'storage'                   => $this->storage,
                    'created_at'                => format_date($this->created_at, '%d. %b. %Y'),
                    'updated_at'                => format_date($this->updated_at, '%d. %B. %Y'),
                ],
                'relationships' => [
                    'settings'   => new SettingsResource($this->settings),
                    'favourites' => new FolderCollection($this->favouriteFolders),
                    $this->mergeWhen($this->hasSubscription(), fn() => [
                        'subscription' => new SubscriptionResource($this->subscription),
                    ]),
                    $this->mergeWhen($isMeteredSubscription, fn() => [
                        'balance' => new BalanceResource($this->balance),
                    ]),
                ],
                'meta'          => [
                    $this->mergeWhen($isFixedSubscription, fn() => [
                        'limitations' => $this->limitations->summary(),
                    ]),
                    $this->mergeWhen($isMeteredSubscription, fn() => [
                        'usages' => $this->getUsageEstimates(),
                    ]),
                ],
            ],
        ];
    }

    private function getUsageEstimates()
    {
        $estimates = $this
            ->subscription
            ->plan
            ->meteredFeatures
            ->map(function ($feature) {

                // Get first tier
                $tier = $feature->tiers()->first();

                $usageQuery = $this->subscription
                    ->usages()
                    ->where('created_at', '>=', now()->subDays(30))
                    ->where('subscription_id', $this->subscription->id)
                    ->where('metered_feature_id', $feature->id);

                $usage = match ($feature->aggregate_strategy) {
                    'sum_of_usage' => $usageQuery->sum('quantity'),
                    'maximum_usage' => $usageQuery->max('quantity'),
                };

                $amount = ($tier->per_unit / 1000) * $usage;

                $formattedUsage = match ($feature->key) {
                    'bandwidth' => Metric::megabytes($usage)->format(),
                    'storage' => Metric::megabytes($usage)->format(),
                };

                // return sum of money
                return [
                    'feature' => $feature->key,
                    'amount'  => $amount,
                    'cost'    => format_currency($amount, $this->subscription->plan->currency),
                    'usage'   => $formattedUsage,
                ];
            });

        return [
            'costEstimate'     => format_currency($estimates->sum('amount'), $this->subscription->plan->currency),
            'featureEstimates' => $estimates->toArray(),
        ];
    }
}
