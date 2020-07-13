<?php

namespace App\Http\Controllers;

use App\Setting;
use App\User;
use Illuminate\Http\Request;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class WebhookController extends CashierController
{
    /**
     * Handle a cancelled customer from a Stripe subscription.
     *
     * @param array $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleCustomerSubscriptionDeleted($payload)
    {
        // Get user
        $user = User::where('stripe_id', $payload['data']['object']['customer'])->firstOrFail();

        // Get default storage capacity
        $default_storage = Setting::where('name', 'storage_default')->first();

        // Update storage capacity
        $user->settings()->update(['storage_capacity' => $default_storage->value]);

        return $this->successMethod();
    }
}
