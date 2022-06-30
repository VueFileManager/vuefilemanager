<?php
namespace App\Users\Resources;

use Domain\Folders\Resources\FolderCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Users\Actions\FormatUsageEstimatesAction;
use Domain\Notifications\Resources\NotificationCollection;
use VueFileManager\Subscription\Domain\Credits\Resources\BalanceResource;
use VueFileManager\Subscription\Domain\CreditCards\Resources\CreditCardCollection;
use VueFileManager\Subscription\Domain\BillingAlerts\Resources\BillingAlertResource;
use VueFileManager\Subscription\Domain\Subscriptions\Resources\SubscriptionResource;
use VueFileManager\Subscription\Domain\Usage\Actions\SumUsageForCurrentPeriodAction;
use VueFileManager\Subscription\Domain\FailedPayments\Resources\FailedPaymentsCollection;

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
                    'color'                     => $this->settings->color,
                    'avatar'                    => $this->settings->avatar,
                    'email'                     => is_demo() ? obfuscate_email($this->email) : $this->email,
                    'role'                      => $this->role,
                    'two_factor_authentication' => (bool) $this->two_factor_secret,
                    'two_factor_confirmed_at'   => $this->two_factor_confirmed_at,
                    'socialite_account'         => ! (bool) $this->password,
                    'storage'                   => $this->storage,
                    'created_at'                => format_date($this->created_at, 'd. M. Y'),
                    'updated_at'                => format_date($this->updated_at, 'd. M. Y'),
                ],
                'relationships' => [
                    'readNotifications'   => new NotificationCollection($this->readNotifications),
                    'unreadNotifications' => new NotificationCollection($this->unreadNotifications),
                    'settings'            => new SettingsResource($this->settings),
                    'favourites'          => new FolderCollection($this->favouriteFolders),
                    'creditCards'         => new CreditCardCollection($this->creditCards),
                    $this->mergeWhen($this->hasSubscription(), fn () => [
                        'subscription' => new SubscriptionResource($this->subscription),
                    ]),
                    $this->mergeWhen($isMeteredSubscription && $this->hasSubscription(), fn () => [
                        'balance' => new BalanceResource($this->balance),
                    ]),
                    $this->mergeWhen($isMeteredSubscription && $this->hasSubscription(), fn () => [
                        'alert' => new BillingAlertResource($this->billingAlert),
                    ]),
                    $this->mergeWhen($isMeteredSubscription && $this->hasSubscription(), fn () => [
                        'failedPayments' => new FailedPaymentsCollection($this->failedPayments),
                    ]),
                ],
                'meta'          => [
                    'restrictions' => [
                        'canUpload'            => $this->canUpload(),
                        'canDownload'          => $this->canDownload(),
                        'canCreateFolder'      => $this->canCreateFolder(),
                        'canCreateTeamFolder'  => $this->canCreateTeamFolder(),
                        'canInviteTeamMembers' => $this->canInviteTeamMembers(),
                        'reason'               => $this->getRestrictionReason(),
                    ],
                    $this->mergeWhen($isFixedSubscription, fn () => [
                        'limitations' => $this->limitations->summary(),
                    ]),
                    $this->mergeWhen($isMeteredSubscription && $this->hasSubscription(), fn () => [
                        'usages' => $this->getUsageEstimates(),
                    ]),
                    $this->mergeWhen($isMeteredSubscription && $this->hasSubscription(), fn () => [
                        'totalDebt' => [
                            'formatted' => format_currency($this->failedPayments->sum('amount'), $this->subscription->plan->currency),
                            'amount'    => $this->failedPayments->sum('amount'),
                        ],
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
