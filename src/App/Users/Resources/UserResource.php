<?php
namespace App\Users\Resources;

use Domain\Folders\Resources\FolderCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Users\Actions\FormatUsageEstimatesAction;
use VueFileManager\Subscription\Domain\CreditCards\Resources\CreditCardCollection;
use VueFileManager\Subscription\Domain\Credits\Resources\BalanceResource;
use VueFileManager\Subscription\Domain\BillingAlerts\Resources\BillingAlertResource;
use VueFileManager\Subscription\Domain\FailedPayments\Resources\FailedPaymentsCollection;
use VueFileManager\Subscription\Domain\Subscriptions\Resources\SubscriptionResource;
use VueFileManager\Subscription\Domain\Usage\Actions\SumUsageForCurrentPeriodAction;

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
                    $this->mergeWhen($this->hasSubscription(), fn () => [
                        'subscription' => new SubscriptionResource($this->subscription),
                    ]),
                    $this->mergeWhen($isMeteredSubscription, fn () => [
                        'balance' => new BalanceResource($this->balance),
                    ]),
                    $this->mergeWhen($isMeteredSubscription, fn () => [
                        'alert' => new BillingAlertResource($this->billingAlert),
                    ]),
                    $this->mergeWhen($isMeteredSubscription, fn () => [
                        'creditCards' => new CreditCardCollection($this->creditCards),
                    ]),
                    $this->mergeWhen($isMeteredSubscription, fn () => [
                        'failedPayments' => new FailedPaymentsCollection($this->failedPayments),
                    ]),
                ],
                'meta'          => [
                    $this->mergeWhen($isFixedSubscription, fn () => [
                        'limitations' => $this->limitations->summary(),
                    ]),
                    $this->mergeWhen($isMeteredSubscription, fn () => [
                        'usages' => $this->getUsageEstimates(),
                    ]),
                    $this->mergeWhen($isMeteredSubscription, fn () => [
                        'totalDebt' => format_currency($this->failedPayments->sum('amount'), $this->subscription->plan->currency),
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
