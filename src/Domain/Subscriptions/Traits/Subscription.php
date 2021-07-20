<?php
namespace Domain\Subscriptions\Traits;

use App\Users\Models\UserSettings;
use Domain\Subscriptions\Services\StripeService;

trait Subscription
{
    /**
     * Get tax rate id for user
     */
    public function userTaxRates(): array
    {
        // Get tax rates
        $rates = collect(
            resolve(StripeService::class)->getTaxRates()
        );

        // Find tax rate
        $user_tax_rate = $rates->first(function ($item) {
            return $item['country'] === $this->settings->country && $item['active'];
        });

        return $user_tax_rate ? [$user_tax_rate['id']] : [];
    }

    /**
     * Set user billing info into user settings table
     */
    public function setBilling($billing): UserSettings
    {
        $this->settings()->update([
            'address'      => $billing['billing_address'],
            'city'         => $billing['billing_city'],
            'country'      => $billing['billing_country'],
            'name'         => $billing['billing_name'],
            'phone_number' => $billing['billing_phone_number'],
            'postal_code'  => $billing['billing_postal_code'],
            'state'        => $billing['billing_state'],
        ]);

        return $this->settings;
    }
}
