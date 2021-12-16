<?php

namespace App\Users\Resources;

use App\Users\Actions\FormatUsageEstimatesAction;
use ByteUnits\Metric;
use Domain\Folders\Resources\FolderCollection;
use VueFileManager\Subscription\Domain\Usage\Actions\SumUsageForCurrentPeriodAction;
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
        // Get plan currency
        $currency = $this->subscription->plan->currency;

        // Get usage
        $usage = resolve(SumUsageForCurrentPeriodAction::class)($this->subscription);

        // Format usages
        $estimates = resolve(FormatUsageEstimatesAction::class)($currency, $usage);

        return [
            'costEstimate'     => format_currency($estimates->sum('amount'), $currency),
            'featureEstimates' => $estimates->toArray(),
        ];
    }
}
