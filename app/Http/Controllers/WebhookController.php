<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class WebhookController extends CashierController
{
    /**
     * Handle a cancelled customer from a Stripe subscription.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleCustomerSubscriptionDeleted($payload) {

        $user = User::where('stripe_id', $payload['data']['object']['customer'])->firstOrFail();

        // TODO: set default capacity
        $user->settings->update(['storage_capacity' => 1]);

        return $this->successMethod();
    }
}
