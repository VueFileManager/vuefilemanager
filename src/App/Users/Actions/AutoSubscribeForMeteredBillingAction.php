<?php
namespace App\Users\Actions;

use App\Users\Models\User;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;

class AutoSubscribeForMeteredBillingAction
{
    public function __invoke(User $user)
    {
        // Get metered billing plan
        $plan = Plan::where('status', 'active')
            ->where('type', 'metered')
            ->first();

        // Get settings
        $settings = get_settings([
            'allowed_registration_bonus',
            'registration_bonus_amount',
        ]);

        // Create user balance
        if (intval($settings['allowed_registration_bonus'])) {
            // Create balance with bonus amount
            $user->balance()->create([
                'amount'   => $settings['registration_bonus_amount'],
                'currency' => $plan->currency,
            ]);

            // Store transaction bonus
            $user->transactions()->create([
                'status'   => 'completed',
                'type'     => 'credit',
                'driver'   => 'system',
                'note'     => __('Bonus'),
                'currency' => $plan->currency,
                'amount'   => $settings['registration_bonus_amount'],
            ]);
        } else {
            // Create balance with 0 amount
            $user->balance()->create([
                'currency' => $plan->currency,
            ]);
        }

        // Create user subscription
        $user->subscription()->create([
            'plan_id'   => $plan->id,
            'name'      => $plan->name,
            'status'    => 'active',
            'renews_at' => now()->addDays(config('subscription.metered_billing.settlement_period')),
            'type'      => 'pre-paid',
        ]);
    }
}
